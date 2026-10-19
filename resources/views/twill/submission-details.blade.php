<div style="padding: 20px 0;">
    <p><strong>Form:</strong> {{ \App\Models\Submission::FORMS[$submission->form] ?? $submission->form }}</p>
    <p><strong>Received:</strong> {{ $submission->created_at }} &middot; <strong>Language:</strong> {{ strtoupper($submission->locale) }}</p>
    <table style="width:100%; border-collapse: collapse; margin-top: 20px;">
        @foreach ($submission->data ?? [] as $key => $value)
            <tr style="border-bottom: 1px solid #eee;">
                <th style="text-align:left; padding: 8px; width: 30%; vertical-align: top;">{{ str_replace('_', ' ', ucfirst($key)) }}</th>
                <td style="padding: 8px; white-space: pre-wrap;">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
            </tr>
        @endforeach
        @foreach ($submission->attachments ?? [] as $key => $path)
            <tr style="border-bottom: 1px solid #eee;">
                <th style="text-align:left; padding: 8px;">{{ str_replace('_', ' ', ucfirst($key)) }}</th>
                <td style="padding: 8px;"><a href="{{ route('submissions.attachment', [$submission->id, $key]) }}" target="_blank">Download</a></td>
            </tr>
        @endforeach
    </table>
</div>
