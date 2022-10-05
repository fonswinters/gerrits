@php
    $stickyClasses = !empty($isSticky) ? "is-sticky  js-sticky-header" : "";
    $headerLogo = $logoOnLight; // can be set to $logoOnDark
@endphp

<header class="o-header  {{$stickyClasses}}" @if(!empty($isSticky)) hidden @endif>

    <div class="o-header__main">
        @include('organisms.skipLink')

        <a class="o-header__logo" href="{{ $links->home->route ?? '/' }}" @if(!empty($isSticky)) tabindex="-1" @endif>
            <img class="o-header__img" alt="Logo {{ config('site.company_name') }}" src="{{$headerLogo}}">
        </a>

        @if(isset($links))
            <div class="o-header__nav">
                @include('components.navigation')
            </div>
        @endif

        @if(config('app.multipleLanguages'))
            <div class="o-header__language">
                @include('components.languageSelect')
            </div>
        @endif

        <div class="o-header__toggle">
            <button class="c-hamburger  js-overlay-menu-trigger" aria-expanded="false" aria-controls="overlay-menu" @if(!empty($isSticky)) tabindex="-1" @endif>
                <span></span>
            </button>
        </div>

    </div>
</header>