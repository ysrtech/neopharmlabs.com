{{-- Job / spontaneous application, using the original careers form markup. Params: $job (nullable) --}}
@php
    $form = $job ? 'job_application' : 'spontaneous_application';
    $err = fn ($name) => $errors->has($name) ? '<p class="fui-error-message">' . e($errors->first($name)) . '</p>' : '';
@endphp
<form method="post" action="{{ route('forms.store', ['form' => $form]) }}" enctype="multipart/form-data" class="apply-form" id="form">
    @csrf
    <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
    <input type="hidden" name="fragment" value="form">
    @if ($job)<input type="hidden" name="subject_id" value="{{ $job->id }}">@endif
    <div class="fui-hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

    @if (session('form_success') === $form)
        <div class="fui-alert fui-alert-success" role="alert"><p>{{ __('site.forms.application_success') }}</p></div>
    @elseif ($errors->any())
        <div class="fui-alert fui-alert-error" role="alert"><p>{{ __('site.forms.error') }}</p></div>
    @endif

    <h2 class="form-section-title">{{ __('site.forms.job_types') }}</h2>
    <div class="form-group">
        <div class="checkbox-group">
            @foreach (__('site.forms.job_type_options') as $option)
                <label class="checkbox-label">
                    <input type="checkbox" name="job_types[]" value="{{ $option }}" @checked(in_array($option, old('job_types', [])))>
                    <span>{{ $option }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <h2 class="form-section-title">{{ __('site.forms.required_info') }}</h2>
    <div class="form-group">
        <label for="email" class="form-label">{{ __('site.forms.email') }} <span class="required">*</span></label>
        <input type="email" id="email" name="email" class="form-input" required value="{{ old('email') }}">
        {!! $err('email') !!}
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="first_name" class="form-label">{{ __('site.forms.first_name') }} <span class="required">*</span></label>
            <input type="text" id="first_name" name="first_name" class="form-input" required value="{{ old('first_name') }}">
            {!! $err('first_name') !!}
        </div>
        <div class="form-group">
            <label for="last_name" class="form-label">{{ __('site.forms.last_name') }} <span class="required">*</span></label>
            <input type="text" id="last_name" name="last_name" class="form-input" required value="{{ old('last_name') }}">
            {!! $err('last_name') !!}
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="phone" class="form-label">{{ __('site.forms.mobile') }} <span class="required">*</span></label>
            <input type="tel" id="phone" name="phone" class="form-input" required value="{{ old('phone') }}" placeholder="(514) 123-4567">
            {!! $err('phone') !!}
        </div>
        <div class="form-group">
            <label for="city" class="form-label">{{ __('site.forms.city') }}</label>
            <input type="text" id="city" name="city" class="form-input" value="{{ old('city') }}">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="work_permit" class="form-label">{{ __('site.forms.work_permit') }} <span class="required">*</span></label>
            <select id="work_permit" name="work_permit" class="form-input" required>
                <option value=""></option>
                @foreach (__('site.forms.work_permit_options') as $option)
                    <option value="{{ $option }}" @selected(old('work_permit') === $option)>{{ $option }}</option>
                @endforeach
            </select>
            {!! $err('work_permit') !!}
        </div>
        <div class="form-group">
            <label for="availability" class="form-label">{{ __('site.forms.availability') }}</label>
            <input type="date" id="availability" name="availability" class="form-input" value="{{ old('availability') }}">
        </div>
    </div>
    <div class="form-group">
        <label for="languages" class="form-label">{{ __('site.forms.languages') }} <span class="required">*</span></label>
        <input type="text" id="languages" name="languages" class="form-input" required value="{{ old('languages') }}" placeholder="{{ __('site.forms.placeholders.languages') }}">
        {!! $err('languages') !!}
    </div>

    <h2 class="form-section-title">{{ __('site.forms.documents') }}</h2>
    @foreach (['cv' => true, 'cover_letter' => false] as $fileField => $fileRequired)
        <div class="form-group">
            <label class="form-label" for="{{ $fileField }}">{{ __('site.forms.' . $fileField) }} @if ($fileRequired)<span class="required">*</span>@endif</label>
            <div class="file-upload">
                <input type="file" id="{{ $fileField }}" name="{{ $fileField }}" class="file-input" accept=".pdf,.doc,.docx" @required($fileRequired)>
                <label for="{{ $fileField }}" class="file-label">
                    <span class="file-text">{{ __('site.forms.choose_file') }}</span>
                </label>
                <span class="file-info">{{ __('site.forms.file_info') }}</span>
            </div>
            {!! $err($fileField) !!}
        </div>
    @endforeach

    <div class="form-group">
        <label for="message" class="form-label">{{ __('site.forms.message') }}</label>
        <textarea id="message" name="message" class="form-input" rows="4">{{ old('message') }}</textarea>
    </div>

    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
            <span>{{ __('site.forms.consent') }} <span class="required">*</span></span>
        </label>
        <details class="consent-details">
            <summary>{{ __('site.forms.consent_more') }}</summary>
            <div class="rich-text">{!! __('site.forms.consent_text') !!}</div>
        </details>
        {!! $err('consent') !!}
    </div>

    <button type="submit" class="btn btn--primary btn--large">{{ __('site.forms.submit_application') }}</button>
</form>
@push('scripts')
    <script>
        document.querySelectorAll('.apply-form .file-input').forEach(function (input) {
            input.addEventListener('change', function () {
                const text = input.parentElement.querySelector('.file-text');
                if (text && input.files.length) { text.textContent = input.files[0].name; }
            });
        });
    </script>
@endpush
