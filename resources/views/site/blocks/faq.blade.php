@php($faqItems = $block->children->where('type', 'faq_item')->sortBy('position'))
<div class="marginTopBottom-M" id="faq">
    @if (bt($block, 'title'))
        <h3 class="h3">{{ bt($block, 'title') }}</h3>
    @endif
    <div class="faq-accordion-block">
        @foreach ($faqItems as $item)
            <div class="faq-accordion-item">
                <button id="faq-accordion-button-{{ $block->id }}-{{ $loop->iteration }}" aria-expanded="false" type="button"><h5 class="faq-accordion-title h5">{{ bt($item, 'question') }}</h5><span class="icon" aria-hidden="true"></span></button>
                <div class="faq-accordion-content">{!! bt($item, 'answer') !!}</div>
            </div>
        @endforeach
    </div>
</div>
@once
    @push('scripts')
        <script>
            (function () {
                const items = document.querySelectorAll('.faq-accordion-block button');
                function toggleAccordion() {
                    const itemToggle = this.getAttribute('aria-expanded');
                    items.forEach(item => item.setAttribute('aria-expanded', 'false'));
                    if (itemToggle === 'false') {
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
                items.forEach(item => item.addEventListener('click', toggleAccordion));
            })();
        </script>
    @endpush
@endonce
