<p>New submission from the website ({{ \App\Models\Submission::FORMS[$submission->form] ?? $submission->form }}, {{ strtoupper($submission->locale) }}).</p>
<table cellpadding="6" style="border-collapse: collapse;">
    @foreach ($submission->data ?? [] as $key => $value)
        <tr>
            <th align="left" valign="top">{{ str_replace('_', ' ', ucfirst($key)) }}</th>
            <td style="white-space: pre-wrap;">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
        </tr>
    @endforeach
</table>
@if (! empty($submission->attachments))
    <p>Attachments can be downloaded from the CMS: {{ url(config('twill.admin_app_path') . '/submissions/' . $submission->id . '/edit') }}</p>
@endif
