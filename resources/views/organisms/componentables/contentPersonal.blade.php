@section('componentable-area-' . $loop->iteration)
    <div class="o-content-personal">
        <div class="o-content-personal__left  s-text">
            @if(isset($component->contentHeader) && $component->contentHeader != '')
                <h2>{!! $component->contentHeader !!}</h2>
            @endif

            @if(isset($component->contentDescription) && $component->contentDescription != '')
                {!! $component->contentDescription !!}
            @endif

            @if(!empty($component->contentButtons) && $component->contentButtons[0]->translation)
                @include('components.button', [
                    'icon' => 'arrowRight',
                    'buttonText'    => $component->contentButtons[0]->translation->label,
                    'buttonLink'    => $component->contentButtons[0]->translation->url,
                ])
            @endif
        </div>
        <div class="o-content-personal__right  s-text">
            @if(isset($component->servicePointHeader) && $component->servicePointHeader != '')
                <h2>{!! $component->servicePointHeader !!}</h2>
            @endif

            @if(!empty($component->servicepoints->first()))
                <div>
                    @include('components.servicePoint', [
                        'servicePoint'  => $component->servicepoints->first(),
                        'cardView'      => false,
                        'buttonType'    => 'text',
                        'buttonText'    => $component->servicePointButtons[0]->translation->label ?? null,
                        'buttonLink'    => $component->servicePointButtons[0]->translation->url ?? null,
                    ])
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@include('organisms.componentables.componentableRow')