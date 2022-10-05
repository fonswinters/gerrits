@if(isset($languageMenu))
    <div class="c-language">

        <button class="c-language__button">
            <span class="c-language__icon">@include('components.icons.speechBubble')</span>
            <span class="c-language__label">{{ strtoupper(\App::getLocale()) }}</span>
        </button>
        <ul class="c-language__dropdown">
            @foreach($languageMenu->withFallback as $languageIso => $route)

                {{-- Skip the current language from menu --}}
                @continue(\App::getLocale() == $languageIso)

                <li class="c-language__item">
                    <a class="c-language__link" href="{{$route}}">
                        {{ strtoupper($languageIso) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif