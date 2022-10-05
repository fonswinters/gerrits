@section('componentable-area-' . $loop->iteration)

<div class="o-usp">
    <div class="o-usp__main">
        <div class="o-usp__text">
            @if(isset($component->header) && $component->header != '')
                <h2 class="o-usp__heading">{!! $component->header !!}</h2>
            @endif
            <ul class="o-usp__list">
                @for($i = 1; $i <= 7; $i++)
                    @if(!empty($component->{'USP'.$i}))
                        <li class="o-usp__item">{!! $component->{'USP'.$i} !!}</li>
                    @endif
                @endfor
            </ul>
                @if(!empty($component->buttons) && $component->buttons[0]->translation)
                <div class="o-usp__action">
                    @include('components.button', [
                        'modifiers' => ['ghost','on-dark'],
                        'icon' => 'arrowRight',
                        'buttonText' => $component->buttons[0]->translation->label,
                        'buttonLink' => $component->buttons[0]->translation->url
                    ])
                </div>
            @endif
        </div>

        <div class="o-usp__image"
             @if(isset($component->image) && $component->image->count() != 0)
                style="background-image: url('{!! $component->image[0]->{'large_image_url'} !!}')">
            @else
                style="background-image: url('/img/placeholder-usp.svg'); ">
            @endif
        </div>
    </div>
</div>
    
@endsection

@include('organisms.componentables.componentableRow')