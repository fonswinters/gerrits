<div class="o-subfooter">
    <div class="o-subfooter__main">
        <div class="o-subfooter__copyright">
            <span>&copy; {{ \Carbon\Carbon::now()->year }} {{ config('site.company_name') }}</span>
        </div>

        <nav class="o-subfooter__nav" role=navigation>
            {{-- Global pages --}}
            <ul class="o-subfooter__list">
                @foreach(['legal','disclaimer','privacy'] as $key => $item)
                    @if(isset($links->{$item}))
                        <li class="o-subfooter__item @if(isset($page->code_name) && $page->code_name == $item) is-active @endif">
                            <a class="o-subfooter__link" href="{{$links->{$item}->route}}">{{$links->{$item}->name}}</a>
                        </li>
                    @endif
                @endforeach
                <li class="o-subfooter__item ">
                    <a class="o-subfooter__link" href="/sitemap">
                        Sitemap
                    </a>
                </li>
            </ul>
        </nav>

        <div class="o-subfooter__trademark">@include('components.trademark')</div>
    </div>
</div>