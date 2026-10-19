<div class="leadgen-box bg-color-light">
    <p class="h5 font-semibold mb-2">{{ __('site.leadgen.title') }}</p>
    <p>{{ __('site.leadgen.text') }}</p>
    @include('site.partials.button', ['url' => page_url('contact'), 'label' => __('site.leadgen.button'), 'style' => 'primary'])
</div>
