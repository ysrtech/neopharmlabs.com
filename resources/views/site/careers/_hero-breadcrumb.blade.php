<div class="breadcrumb">
    <a href="{{ lroute('home') }}">{{ __('site.careers.home') }}</a>
    <span class="separator">/</span>
    <a href="{{ page_url('careers') }}">{{ __('site.menu.careers') }}</a>
    @foreach ($crumbs ?? [] as [$crumbUrl, $crumbLabel])
        <span class="separator">/</span>
        <a href="{{ $crumbUrl }}">{{ $crumbLabel }}</a>
    @endforeach
    <span class="separator">/</span>
    <span class="current">{{ $current }}</span>
</div>
