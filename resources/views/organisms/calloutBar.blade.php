@if(!empty($servicePointHeading))
    <div class="o-callout-bar">
        <div class="o-callout-bar__main">

            @if(config('site.global_CTA_heading'))
                <h2 class="o-callout-bar__heading  c-heading">{!! config('site.global_CTA_heading') !!}</h2>
            @endif

            @include('components.connect', [
                'phoneDisplay' => $servicePoint->translation->telephone_label ?? null,
                'phoneCall' => $servicePoint->translation->telephone_url ?? null,
                'buttonText' => App\Buttons\Models\Button::where('id', config('site.global_CTA_button_id'))->with('translations')->first()->translation->label ?? ($servicePointButton->translation->label ?? null),
                'buttonLink' => App\Buttons\Models\Button::where('id', config('site.global_CTA_button_id'))->with('translations')->first()->translation->url ?? ($servicePointButton->translation->url ?? null),
            ])

        </div>
    </div>
@endif