@if(isset($servicePoint))

<div class="c-servicepoint  @modifiers('c-servicepoint')">
    <picture class="c-servicepoint__picture">
        <img class="c-servicepoint__img"
             src="{{ ($servicePoint->documents && count($servicePoint->documents) > 0) ? $servicePoint->documents[0]->small_image_url : '/img/placeholder-person.svg'}}"
             width="176" height="176"
             @if(!empty($servicePoint->translation->first_name) || !empty($servicePoint->translation->last_name))
                 alt="{{$servicePoint->translation->first_name}} {{$servicePoint->translation->last_name}}"
            @endif
        >
    </picture>
    <div class="c-servicepoint__content">
        @if(!empty($servicePoint->translation->first_name) || !empty($servicePoint->translation->last_name) || !empty($servicePoint->translation->function))
            <div class="c-servicepoint__header">
                @if(!empty($servicePoint->translation->first_name) || !empty($servicePoint->translation->last_name))
                    <h3 class="c-servicepoint__heading">@if(!empty($servicePoint->translation->first_name)) {{$servicePoint->translation->first_name}} @endif @if(!empty($servicePoint->translation->last_name)) {{$servicePoint->translation->last_name}} @endif</h3>
                @endif

                @if(!empty($servicePoint->translation->function) && (isset($cardView) && $cardView))
                    <p class="c-servicepoint__subheading">{{$servicePoint->translation->function}}</p>
                @endif
            </div>
        @endif

        @if(!empty($servicePoint->translation->telephone_label) || !empty($servicePoint->translation->email))
            <div class="c-servicepoint__body">

                @if((isset($cardView) && $cardView))
                    <div class="c-data">
                        @if(!empty($servicePoint->translation->telephone_label))
                            <p class="c-data__line">
                                <span class="c-data__label">Tel: </span>
                                @if(!empty($servicePoint->translation->telephone_url))
                                    <a class="c-data__value"
                                       href="tel:{{$servicePoint->translation->telephone_url}}">{{$servicePoint->translation->telephone_label}}</a>
                                @else
                                    <span class="c-data__value">{{$servicePoint->translation->telephone_label}}</span>
                                @endif
                            </p>
                        @endif
                        @if(!empty($servicePoint->translation->email))
                            <p class="c-data__line">
                                <span class="c-data__label">E-mail:</span>
                                <a class="c-data__value" href="mailto:{{$servicePoint->translation->email}}">{{$servicePoint->translation->email}}</a>
                            </p>
                        @endif
                    </div>

                @else
                    @if(!empty($servicePoint->translation->telephone_label))
                        <a class="c-servicepoint__tel"
                           href="tel:{!! $servicePoint->translation->telephone_url !!}">{!! $servicePoint->translation->telephone_label !!}</a>
                    @endif
                @endif


            </div>
        @endif

        @if(isset($buttonText) && isset($buttonLink))

            <div class="c-servicepoint__footer">

                @include('components.button', [
                    'modifiers' => isset($buttonType) && $buttonType == 'text' ? 'text' : '',
                    'icon' => 'arrowRight',
                    'buttonText' => $buttonText,
                    'buttonLink' => $buttonLink
                ])

            </div>
        @endif

    </div>
</div>

@endif