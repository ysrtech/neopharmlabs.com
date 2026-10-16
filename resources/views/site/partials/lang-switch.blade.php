@php($names = ['en' => 'English', 'fr' => 'Français'])
<span class="lang nav-link hasDropdown">
    @include('site.svg.globe')
    {{ $names[app()->getLocale()] }} @include('site.svg.chevron-down')
</span>
<div class="dropDownNav">
    <ul>
        @foreach ($names as $code => $name)
            <li class="{{ app()->getLocale() === $code ? 'active' : '' }}">
                <a href="{{ $alternates[$code] ?? lroute('home', [], $code) }}" hreflang="{{ $code }}" lang="{{ $code }}">
                    <span>{{ $name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
