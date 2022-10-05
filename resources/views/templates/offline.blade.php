@extends('master', [ 'hideCookieMessage' => true])

@section('title', 'Offline | '. config('site.company_name'))

@section('content')

    @component('organisms.intro')
        @slot('header')
            @include('components.pageTitle', [
			  'model' => null,
			  'isInsideIntro' => true,
			  'heading' => 'Offline',
			  'subHeading' => 'Check your internet connection and try again!'
		   ])
        @endslot
        <div class="o-intro__content">
            <div class="o-intro__text  s-text">
                <p>Unfortunately, the page you requested cannot be loaded. We apologize for the inconvenience.</p>

                <button class="c-button" onclick="window.location.reload()">
                    <span class="c-button__label">Reload page</span>
                </button>
            </div>
            <aside class="o-intro__image">
                <div class="c-projector">
                    <figure class="c-projector__figure">
                        <picture class="c-projector__picture  is-active">
                            <img class="c-projector__img" src="/img/error.jpg" alt="">
                        </picture>
                    </figure>
                </div>
            </aside>
        </div>
    @endcomponent

@endsection