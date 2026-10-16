@php
    $locale = app()->getLocale();
    $seo = $seo ?? [];
    $title = $seo['title'] ?? null ?: 'Neopharm | ' . ($locale === 'fr' ? 'Services analytiques innovants pour les sciences de la vie' : 'Innovative analytical services for the life sciences');
    $description = $seo['description'] ?? null ?: ($locale === 'fr'
        ? 'Depuis plus de 30 ans, Neopharm Labs s’est bâti une réputation de qualité, de capacité à résoudre les problèmes et d’expérience client supérieure.'
        : 'For over 30 years, Neopharm Labs has built a reputation for quality, problem‑solving capabilities, and a superior customer experience.');
    $ogImage = $seo['image'] ?? asset('assets/img/og-default.jpg');
    $alternates = $alternates ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta charset="utf-8"/>
    <title>{{ $title }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 300) }}">
    <meta name="robots" content="{{ config('app.env') === 'production' ? 'all' : 'noindex, nofollow' }}">
    <link rel="canonical" href="{{ url()->current() }}">
    @foreach ($alternates as $altLocale => $altUrl)
        <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ $altUrl }}">
    @endforeach
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta content="origin-when-cross-origin" name="referrer">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="/assets/css/style.css?v=20260918" type="text/css">
    <link rel="stylesheet" href="/assets/css/rebuild.css?v=1" type="text/css">

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 300) }}" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($description), 300) }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (config('app.env') === 'production' && config('site.google_analytics'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.google_analytics') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('site.google_analytics') }}');
        </script>
    @endif
    @stack('head')
</head>
<body class="{{ $bodyClass ?? '' }}">
    <div class="content-container">
        @include('site.partials.header')

        @yield('content')

        @include('site.partials.footer')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="/assets/js/libs/lozad.min.js"></script>
    <script src="/assets/js/libs/featherlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mixitup/2.1.11/jquery.mixitup.min.js"></script>
    <script src="/assets/js/libs/swiper-bundle.min.js"></script>
    <script src="/assets/js/scripts.js?v=20260918"></script>
    @stack('scripts')
</body>
</html>
