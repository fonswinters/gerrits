@if (isset($paginator) && $paginator->hasPages())

    <ul class="c-pagination">

        {{--Previous Page--}}
        <li class="c-pagination__item">
            @if($paginator->previousPageUrl())
                <a class="c-pagination__prev  c-pagination__link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    @include('components.icons.arrowhead')
                </a>
            @else
                <span class="c-pagination__prev  c-pagination__text">@include('components.icons.arrowhead')</span>
            @endif
        </li>

        {{--First Page--}}
        <li class="c-pagination__item  is-first-page  @if($paginator->currentPage() == 1) is-active @endif">
            @if($paginator->currentPage() != 1)
                <a class="c-pagination__link" href="{{ $paginator->url(1) }}">1</a>
            @else
                <span class="c-pagination__link">1</span>
            @endif
        </li>


        {{-- Now the hard part, the middle --}}

        {{-- All pages can be shown, because there are less then 7 so no dots --}}
        @if($paginator->lastPage() <=  7)

            @for($paginationPage = 2; $paginationPage < $paginator->lastPage() ; $paginationPage++)

                @include('components.pagination.item')

            @endfor

            {{-- Dotted pagination needed --}}
        @else

            {{-- Current page is below 4, so first 6 items can be shown --}}
            @if($paginator->currentPage() <= 4)

                @for($paginationPage = 2; $paginationPage <= 6 ; $paginationPage++)

                    @include('components.pagination.item')

                @endfor

                {{-- Dots are needed in the end --}}
                @include('components.pagination.dots')


                {{-- Current page is one of the last 4, so the last 6 items can be shown --}}
            @elseif($paginator->currentPage() >= ($paginator->lastPage() - 3) )

                {{-- Dots are needed at the start --}}
                @include('components.pagination.dots')

                @for($paginationPage = ($paginator->lastPage() - 5); $paginationPage < $paginator->lastPage(); $paginationPage++)

                    @include('components.pagination.item')

                @endfor

                {{-- Dots before and after --}}
            @else
                @include('components.pagination.dots')

                @for($paginationPage = ($paginator->currentPage() - 2); $paginationPage <= ($paginator->currentPage() + 2); $paginationPage++)

                    @include('components.pagination.item')

                @endfor

                @include('components.pagination.dots')

            @endif

        @endif


        {{--Last page--}}
        <li class="c-pagination__item  is-last-page  @if($paginator->currentPage() == $paginator->lastPage()) is-active @endif">
            @if($paginator->currentPage() !== $paginator->lastPage())
                <a class="c-pagination__link" href="{{ $paginator->url($paginator->lastPage()) }}">{{$paginator->lastPage()}}</a>
            @else
                <span class="c-pagination__link">{{$paginator->lastPage()}}</span>
            @endif
        </li>

        {{--Next Page--}}
        <li class="c-pagination__item">
            @if($paginator->nextPageUrl())
                <a class="c-pagination__next  c-pagination__link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    @include('components.icons.arrowhead')
                </a>
            @else
                <span class="c-pagination__next  c-pagination__text">@include('components.icons.arrowhead')</span>
            @endif
        </li>

    </ul>

@endif