@section('componentable-area-' . $loop->iteration)

    <div class="o-text-image @if($component->reversed) o-text-image--is-reversed @endif">
        <div class="o-text-image__i">
            @include('components.image', [
                'images' => $component->image,
                'imageId' => 'componentable-area-' . $loop->iteration.'-slider',
                'caption' => $component->caption,
            ])
        </div>
        <div class="o-text-image__t  s-text">
            {!! !empty($component->text) ? $component->text : config('atomic.text') !!}

            @if(!empty($component->buttons) && $component->buttons[0]->translation)
                @include('components.button', [
                    'icon' => 'arrowRight',
                    'buttonText' => $component->buttons[0]->translation->label,
                    'buttonLink' => $component->buttons[0]->translation->url
                ])
            @endif
        </div>
    </div>

@endsection

@include('organisms.componentables.componentableRow')