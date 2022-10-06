@php
    $footerLogo = $logoOnDark; // can be set to $logoOnLight
@endphp

<footer>
    <div class="o-footer">
        <div class="o-footer__main">

            <a class="o-footer__brand" href="/@if(\App::getLocale() != 'nl'){{\App::getLocale()}}@endif" tabindex="-1">
                <img class="o-footer__logo" src="{{$footerLogo}}" alt=""/>
            </a>


            <div class="o-footer__info">
                <div class="o-footer__address">
                    <p>{{ config('site.company_name') }}</p>
                    <p>{{ config('site.company_address') }}</p>
                    <p>{{ config('site.company_zip') }} {{ config('site.company_city') }}</p>
                    <p>{{ config('site.company_country') }}</p>
                </div>

                <div class="o-footer__contact">
                    <ul class="o-footer__list">
                        <li class="o-footer__item">
                            <a class="o-footer__link" href="tel:{{ config('site.company_phone_call') }}">{{ config('site.company_phone_display') }}</a><br/>
                        </li>
                        <li class="o-footer__item">
                            <a class="o-footer__link" href="mailto:{{ config('site.company_email') }}">{{ config('site.company_email') }}</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{--Social Media --}}
            <ul class="o-footer__social">
                @foreach(['instagram','linkedin','facebook','twitter'] as $item)
                    @if(config('site.company_social_'.$item) != null)
                        <li class="o-footer__channel  {{$item}}">
                            <a class="o-footer__icon" target="_blank" rel="noopener noreferrer" title="{{ ucfirst($item) }}" href="{{ config('site.company_social_'.$item) }}"></a>
                        </li>
                    @endif
                @endforeach
            </ul>


            {{-- Navigation links --}}
            <nav class="o-footer__nav" role=navigation>
                <ul class="o-footer__list">
                    @foreach($navItems as $item)
                        @if(isset($links->{$item->code_name}))
                            <li class="o-footer__item @if(isset($page->code_name) && $page->code_name == $item->code_name) is-active @endif">
                                <a class="o-footer__link" href="{{ $links->{$item->code_name}->route }}">{{$links->{$item->code_name}->name}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <div class="badges">
                <img src="/img/leerbedrijf.png" alt="Erkend leerbedrijf" class="badge">
            </div>

        </div>
    </div>

    @include('organisms.subfooter')

</footer>