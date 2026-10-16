{{-- Shared form opening: CSRF, locale, honeypot and status messages. Params: $form, $fragment --}}
<form class="fui-form {{ ($white ?? false) ? 'white-text-form' : '' }}" method="post" action="{{ route('forms.store', ['form' => $form]) }}" enctype="multipart/form-data" accept-charset="utf-8">
    @csrf
    <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
    <input type="hidden" name="fragment" value="{{ $fragment ?? 'form' }}">
    <div class="fui-hp" aria-hidden="true"><label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    @if (session('form_success') === $form)
        <div class="fui-alert fui-alert-success" role="alert">
            <p>{{ $successMessage ?? __('site.forms.success') }}</p>
            @if (session('download_url'))
                <p><a class="btn primary" href="{{ session('download_url') }}" target="_blank" rel="noopener">{{ __('site.forms.download_link') }}</a></p>
            @endif
        </div>
    @elseif ($errors->any() && old('fragment', 'form') === ($fragment ?? 'form'))
        <div class="fui-alert fui-alert-error" role="alert"><p>{{ __('site.forms.error') }}</p></div>
    @endif
