@section('componentable-area-' . $loop->iteration)

    <div class="o-block  u-space-mt8">
        @if(!empty($component->title))
            <div class="l-contain">
                <h2 class="c-heading">{!! $component->title !!}</h2>
            </div>
        @endif

        <div class="o-vacancy-process  u-space-mt4">
            <div class="o-vacancy-process__p  u-space-pl0">

                <ul class="c-toggle  js-toggle">
                    @foreach($component->process as $key => $step)
                        <li class="c-toggle__item @if($loop->index === 0) is-active @endif  @if(!empty($step)) is-clickable @endif">
                            <h2 class="c-toggle__title  @if(!empty($step)) js-toggle-switch @endif">
                                <span class="c-toggle__icon"></span>
                                <span class="c-toggle__label">{{ $step->translation->name ?? '' }}</span>
                            </h2>
                            <div class="c-toggle__content  js-toggle-content">
                                {!! $step->translation->description ?? '🤷🏻' !!}
                            </div>
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="o-vacancy-process__s">
                @if(!empty($component->servicepoint_title))
                    <h2 class="c-heading  u-space-mb3">{{$component->servicepoint_title}}</h2>
                @endif
                @if(!empty($servicePointButton->translation->label) || !empty($servicePointButton->translation->url))
                    @include('components.servicePoint', [
                        'cardView' => false,
                        'buttonType'    => 'text',
                        'buttonText' => $servicePointButton->translation->label ?? '',
                        'buttonLink' => $servicePointButton->translation->url ?? '',
                    ])
                @endif
            </div>
        </div>
    </div>

@endsection

@include('organisms.componentables.componentableRow')

