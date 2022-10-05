@section('componentable-area-' . $loop->iteration)

@php $componentLoop = $loop; @endphp

<div class="o-tabslider  js-tabslider">
    <div class="o-tabslider__controls">
        <button class="o-tabslider__button  o-tabslider__step  o-tabslider__step--prev  js-tabslider-step" data-step="prev">
            @include('components.icons.arrowRight')
        </button>
        <div class="o-tabslider__tabs">
            @foreach($component->tabs as $tab)
                @if(!$tab->image->isEmpty() || $tab->label || $tab->header || $tab->text)
                    <button class="o-tabslider__button  o-tabslider__tab  js-tabslider-trigger @if($tab->id == 1) is-active @endif" data-tab-id="{{$tab->id}}" @if($tab->id == 1) tabindex="-1" @endif>
                        @if(!empty($tab->label))
                            {{ $tab->label }}
                        @else
                            {{'Tab '.$tab->id }}
                        @endif
                    </button>
                @endif
            @endforeach
        </div>
        <button class="o-tabslider__button  o-tabslider__step  o-tabslider__step--next  js-tabslider-step" data-step="next">
            @include('components.icons.arrowRight')
        </button>
    </div>
    <div class="o-tabslider__container  js-tabslider-container">

        @foreach($component->tabs as $tab)
            @if(!$tab->image->isEmpty() || $tab->label || $tab->header || $tab->text)
                <div class="o-tabslider__content  js-tabslider-content @if($tab->id == 1) is-active @endif" data-tab-id="{{$tab->id}}">
                    <div class="o-text-image  @if($tab->reversed) o-text-image--is-reversed @endif">
                            <div class="o-text-image__i">
                                @include('components.image', ['images' => $tab->image, 'imageId' => 'componentable-area-' . $componentLoop->iteration.'-slider' ])
                            </div>
                        <div class="o-text-image__t">
                            @if(!empty($tab->label)) <p class="o-tabslider__label">{{ $tab->label }}</p> @endif
                            <div class="s-text">
                                @if(!empty($tab->header)) <h2>{!! $tab->header !!}</h2> @endif
                                @if(!empty($tab->text)) {!! $tab->text  !!} @endif
                                @if(!empty($tab->buttons) > 0 && $tab->buttons[0]->translation)
                                    @include('components.button', [
                                        'icon' => 'arrowRight',
                                        'buttonText' => $tab->buttons[0]->translation->label,
                                        'buttonLink' => $tab->buttons[0]->translation->url,
                                    ])
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>

@endsection

@include('organisms.componentables.componentableRow')
