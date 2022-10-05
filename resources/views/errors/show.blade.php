@extends('master', [ 'hideCookieMessage' => true])

@section('title', $exception->getStatusCode())

@section('content')

    @component('organisms.intro')
        @slot('header')
            @include('components.pageTitle', [
               'model' => null,
               'isInsideIntro' => true,
               'backToLabel' => __('vacancies.backToLabel'),
               'heading' => __('errors.errorHeading'),
               'subHeading' => 'Foutcode' . $exception->getStatusCode()
            ])
        @endslot
        <div class="o-intro__content">
            <div class="o-intro__text  s-text">
                @if( __('errors.' . $exception->getStatusCode() ) !== 'errors.' . $exception->getStatusCode() )
                    <h2>{!! __('errors.' . $exception->getStatusCode()) !!}</h2>
                @else
                    <h2>{!! __('errors.default') !!}</h2>
                @endif

                @if($exception->getMessage())
                    <p>{{ $exception->getMessage() }}</p>
                @endif

                @include('components.button', [
					'buttonText' => __('errors.homeButton'),
					'buttonLink' => '/'
				])
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

    @include('organisms.calloutBar', [
        'heading' => __('errors.cta_heading'),
    ])

@endsection