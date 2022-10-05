@section('componentable-area-' . $loop->iteration)
    <div class="l-contain">
        <div class="l-restrict  s-text" style="--max-columns: 6">
            {!! !empty($component->text_text) ? $component->text_text : config('atomic.text') !!}

            @if(!empty($component->text_buttons) && $component->text_buttons[0]->translation)
                @include('components.button', [
                    'icon' => 'arrowRight',
                    'buttonText' => $component->text_buttons[0]->translation->label,
                    'buttonLink' => $component->text_buttons[0]->translation->url
                ])
            @endif
        </div>
    </div>

@endsection

@include('organisms.componentables.componentableRow')