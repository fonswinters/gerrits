@php
    $overlayMenuLogo = $logoOnDark; // can be set to $logoOnLight
@endphp

<div class="o-overlay-menu  js-overlay-menu" id="overlay-menu" hidden>

    <div class="o-overlay-menu__bar">
        <a class="o-overlay-menu__logo" href="{{ $links->home->route ?? '/' }}" tabindex="-99">
            <img class="o-overlay-menu__img" alt="Logo {{ config('site.company_name') }}" src="{{$overlayMenuLogo}}">
        </a>

        <div class="o-overlay-menu__toggle">
            <button class="c-close  js-overlay-menu-trigger" aria-expanded="true" aria-controls="overlay-menu" tabindex="-1"></button>
        </div>
    </div>

    <nav class="o-overlay-menu__nav" role=navigation>
        <ul class="o-overlay-menu__list">

            @foreach($navItems as $navKey => $navItem)
                @continue(empty($links->{$navItem->code_name}))
                <li class="o-overlay-menu__item">
                    <a class="o-overlay-menu__link @if(isset($page->code_name) && $page->code_name == $navItem->code_name) is-active @endif" href="{{ $links->{$navItem->code_name}->route }}" tabindex="-1">
                        {{ $links->{$navItem->code_name}->name }}
                    </a>
                </li>
            @endforeach

        </ul>
    </nav>
</div>