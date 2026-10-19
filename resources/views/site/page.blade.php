@extends('site.layouts.app')

@php
    $blocks = root_blocks($page);
    // Full-width blocks render outside the content wrapper, like on the original site.
    $outside = ['hero', 'services_cards', 'locations'];
    $bodyClass = ['home' => 'homepage', 'contact' => 'contactUsWorld', 'legal' => 'legal'][$page->template] ?? '';
    $headerClass = in_array($page->template, ['home', 'contact']) ? 'is-white' : '';
@endphp

@section('content')
    @foreach ($blocks->takeWhile(fn ($b) => in_array($b->type, $outside)) as $block)
        @include('site.blocks.' . $block->type, ['block' => $block])
    @endforeach

    @php($body = $blocks->skipWhile(fn ($b) => in_array($b->type, $outside)))
    @if ($body->isNotEmpty())
        @if ($page->template === 'legal' || $body->every(fn ($b) => in_array($b->type, ['text', 'image', 'quote', 'video', 'cta', 'faq'])))
            <div class="wrap wrap-M marginTopBottom-M">
                <div class="content-column">
                    @foreach ($body as $block)
                        @include('site.blocks.' . $block->type, ['block' => $block])
                    @endforeach
                </div>
            </div>
        @else
            <div class="matrix_body_blocks">
                <div class="wrap">
                    @foreach ($body as $block)
                        @if (in_array($block->type, $outside))
                            </div>@include('site.blocks.' . $block->type, ['block' => $block])<div class="wrap">
                        @else
                            @include('site.blocks.' . $block->type, ['block' => $block])
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
@endsection
