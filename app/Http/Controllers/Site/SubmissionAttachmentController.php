<?php

namespace App\Http\Controllers\Site;

use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class SubmissionAttachmentController extends Controller
{
    public function __invoke(Submission $submission, string $key)
    {
        $path = $submission->attachments[$key] ?? null;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }
}
