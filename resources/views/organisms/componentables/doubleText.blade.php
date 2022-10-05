@section('componentable-area-' . $loop->iteration)
    <div class="o-double-text  l-contain">
        <div class="o-double-text__a  s-text">
            {!! $component->double_text_left !!}

            @if(!empty($component->double_text_button_left) && $component->double_text_button_left[0]->translation)
                @include('components.button', [
                    'icon' => 'arrowRight',
                    'buttonText' => $component->double_text_button_left[0]->translation->label,
                    'buttonLink' => $component->double_text_button_left[0]->translation->url
                ])
            @endif
        </div>
        <div class="o-double-text__b  s-text">
            {!! $component->double_text_right !!}

            @if(!empty($component->double_text_button_right) && $component->double_text_button_right[0]->translation)
                @include('components.button', [
                    'icon' => 'arrowRight',
                    'buttonText' => $component->double_text_button_right[0]->translation->label,
                    'buttonLink' => $component->double_text_button_right[0]->translation->url
                ])
            @endif
        </div>
    </div>

@endsection

@include('organisms.componentables.componentableRow')