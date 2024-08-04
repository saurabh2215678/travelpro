@if ($paginator->hasPages())
<div class="pagination">
    <ul class="pagination_list">

    @if ($paginator->onFirstPage())
        <li class="page-item disabled">
            <a href="#"><img src="{{asset(config('custom.assets').'/images/next.png')}}" alt=""></a>
        </li>
    @else
        <li class="page-item">
            <a  class="page-link" href="{{ $paginator->previousPageUrl() }}"><img src="{{asset(config('custom.assets').'/images/next.png')}}" alt=""></a>
        </li>
    @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach


        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><img src="{{asset(config('custom.assets').'/images/prev.png')}}" alt="next"></a>

            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#"><img src="{{asset(config('custom.assets').'/images/prev.png')}}" alt="next"></a>
            </li>
        @endif
   </ul>
</div>
@endif