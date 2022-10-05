<li class="c-pagination__item @if($paginator->currentPage() == $paginationPage) is-active @endif">
    @if($paginator->currentPage() != $paginationPage)
        <a class="c-pagination__link" href="{{ $paginator->url($paginationPage) }}" >{{ $paginationPage }}</a>
    @else
        <span class="c-pagination__link" >{{ $paginationPage }}</span>
    @endif
</li>