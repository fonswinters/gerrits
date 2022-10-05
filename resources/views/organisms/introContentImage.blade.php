@php
    $model = $model ?? $page;
    $intro_image = $model->hero->documents->first()->large_image_url ?? '/img/placeholder-hero.svg';
    $intro_active = $model->hero->active;
    $intro_title = $model->hero->title;
    $intro_description = $model->hero->description;
    $intro_buttons = $model->hero->buttons;
@endphp

@component('organisms.intro')
    @slot('header')
        @include('components.pageTitle', ['isInsideIntro' => true])
    @endslot
    @if($intro_active)
        <div class="o-intro__content">
            <div class="o-intro__text  s-text">
                @if(!empty($intro_title))
                    <h2>{{$intro_title}}</h2>
                @endif

                @if(!empty($intro_description))
                    {!! $intro_description !!}
                @endif

                @includeWhen(!empty($intro_buttons),'components.button', [
					'icon' => 'arrowRight',
					'buttonText' => $intro_buttons->translation->label ?? '',
					'buttonLink' => $intro_buttons->translation->url ?? '',
				])
            </div>
            <aside class="o-intro__image">
                @if(!$model->hero->documents->isEmpty())
                    @include('components.image', [
						'images' => $model->hero->documents,
						'imageId' => 'hero-slider',
						'imageNavigationMethod' => 0,
						'imageAutoSlide' => true
					])
                @else
                    <img class="u-placeholder-image" src="{{$intro_image}}"/>
                @endif
            </aside>
        </div>
    @endif
@endcomponent