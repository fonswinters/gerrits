@section('componentable-area-' . $loop->iteration)
    <div class="o-downloads  @if($component->reversed) is-reversed @endif">
        <div class="o-downloads__main">
            <div class="o-downloads__d">
                <h2 class="c-heading  u-space-mb3">{{ $component->download_title }}</h2>

                <ul class="c-download-list">
                    @foreach($component->downloads as $download)
                        <li class="c-download-list__item">
                            <a class="c-download-list__link"
                               href="{{ $download->getFileUrlAttribute() }}"
                               @if(\Illuminate\Support\Str::endsWith($download->getFileUrlAttribute(), '.pdf')) target="_blank" rel="noopener noreferrer"
                               @else download @endif>

                                <span class="c-download-list__icon">@include('components.icons.download')</span>
                                <span class="c-download-list__text">
                                    {{ $download->name }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="o-downloads__c  s-text">
                @if(isset($component->personal_header) && $component->personal_header != '')
                    <h2 class="c-heading">{!! $component->personal_header !!}</h2>
                @endif

                @if(!empty($component->servicepoints) && !empty($component->servicepoints->first()))
                    <div>
                        @include('components.servicePoint', [
                            'servicePoint'  => $component->servicepoints->first(),
                            'cardView'      => true,
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