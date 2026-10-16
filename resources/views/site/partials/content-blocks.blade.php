@foreach (root_blocks($item) as $block)
    @includeIf('site.blocks.' . $block->type, ['block' => $block])
@endforeach
