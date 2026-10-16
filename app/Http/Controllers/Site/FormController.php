<?php

namespace App\Http\Controllers\Site;

use App\Mail\SubmissionReceived;
use App\Models\JobPosting;
use App\Models\Resource;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function store(Request $request, string $form)
    {
        app()->setLocale(in_array($request->input('locale'), array_keys(config('site.locales'))) ? $request->input('locale') : 'en');

        // Honeypot: bots fill every field.
        if (filled($request->input('website'))) {
            return back()->with('form_success', $form);
        }

        $rules = $this->rules($form);
        $data = $request->validate($rules);

        $attachments = [];
        foreach (['cv', 'cover_letter'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $attachments[$fileKey] = $request->file($fileKey)->store('submissions/' . now()->format('Y/m'), 'local');
                unset($data[$fileKey]);
            }
        }

        $subject = null;
        if ($form === 'resource') {
            $subject = Resource::query()->published()->findOrFail($request->integer('subject_id'));
        } elseif ($form === 'job_application') {
            $subject = JobPosting::query()->published()->findOrFail($request->integer('subject_id'));
        }
        unset($data['subject_id']);

        $name = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        $submission = Submission::create([
            'published' => true,
            'title' => Str::limit(trim((Submission::FORMS[$form] ?? $form) . ': ' . ($name ?: ($data['email'] ?? '')) . ($subject ? ' — ' . $subject->title : '')), 250),
            'form' => $form,
            'locale' => app()->getLocale(),
            'email' => $data['email'] ?? null,
            'subject_type' => $subject ? class_basename($subject) : null,
            'subject_id' => $subject?->id,
            'data' => $data + ($subject ? ['regarding' => $subject->title] : []),
            'attachments' => $attachments,
            'ip' => $request->ip(),
        ]);

        $this->notify($submission);

        $response = back()->with('form_success', $form);
        if ($form === 'resource' && $subject && $subject->file('download')) {
            $response->with('download_url', $subject->file('download'));
        }

        return $response->withFragment($request->input('fragment', 'form'));
    }

    protected function rules(string $form): array
    {
        $base = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
        ];

        return match ($form) {
            'contact', 'privacy_request' => $base + [
                'phone' => 'required|string|max:50',
                'address' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:100',
                'message' => 'required|string|max:5000',
            ],
            'resource' => $base + [
                'message' => 'nullable|string|max:5000',
                'subject_id' => 'required|integer',
            ],
            'job_application', 'spontaneous_application' => $base + [
                'subject_id' => $form === 'job_application' ? 'required|integer' : 'nullable',
                'phone' => 'required|string|max:50',
                'city' => 'nullable|string|max:100',
                'job_types' => 'nullable|array',
                'job_types.*' => 'string|max:50',
                'work_permit' => 'required|string|max:100',
                'languages' => 'required|string|max:255',
                'availability' => 'nullable|date',
                'message' => 'nullable|string|max:5000',
                'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
                'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'consent' => 'accepted',
            ],
        };
    }

    protected function notify(Submission $submission): void
    {
        $group = match ($submission->form) {
            'job_application', 'spontaneous_application' => 'careers',
            'privacy_request' => 'privacy',
            default => 'contact',
        };
        $to = array_filter(array_map('trim', explode(',', (string) config("site.notify.$group"))));
        if (! $to) {
            return;
        }

        try {
            Mail::to($to)->send(new SubmissionReceived($submission));
        } catch (\Throwable $e) {
            // The submission is already stored in the CMS; a mail outage must not lose the lead.
            Log::warning('Form notification failed: ' . $e->getMessage(), ['submission' => $submission->id]);
        }
    }
}
