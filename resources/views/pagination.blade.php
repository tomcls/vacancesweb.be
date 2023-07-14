@if ($paginator->hasPages())
<ul class="flex flex-row space-x-1">
    @if ($paginator->onFirstPage())
        <li class="disabled px-3 py-1 text-gray-400"><span>← Previous</span></li>
    @else
        <li class="px-3 py-1 text-sky-600"><a wire:model.prevent="page" href="{{ $paginator->previousPageUrl() }}" rel="prev">← Previous</a></li>
    @endif
    @foreach ($elements as $element)
        @if (is_string($element))
            <li class="disabled px-3 py-1"><span>{{ $element }}</span></li>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="active my-active px-3 py-1"><span>{{ $page }}</span></li>
                @else
                    <li class="px-3 py-1 bg-slate-200 hover:bg-slate-400 rounded"><a wire:click.prevent="gotoPage({{$page}})" href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach
        @endif
    @endforeach
    @if ($paginator->hasMorePages())
        <li class="px-3 py-1 text-sky-600"><a wire:model.prevent="page" href="{{ $paginator->nextPageUrl() }}" rel="next" >Next →</a></li>
    @else
        <li class="disabled px-3 py-1 text-gray-400"><span>Next →</span></li>
    @endif
</ul>
@endif 