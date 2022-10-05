@if(!empty($title))
    <h2 class="c-heading  u-space-mb3">
        {{$title}}
    </h2>
@endif

@if(isset($cardItems))
    <div class="o-card-grid">
        @foreach($cardItems as $item)
            @php
                // When we only get an array with names of the pages, we first set the model & link
                    $showCard = true;
                    if(isset($modelThroughLinks) && $modelThroughLinks) {
                        if(property_exists($links, $item)) {
                            $model = $links->{$item}->node;
                            $link = $links->{$item}->route;
                        } else {
                            $showCard = false;
                        }
                    }
                    // When we get real models of the pages we want to show
                    else {
                        $model = $item;
                        $link = $modelTypeRoute . '/' . $model->translation->slug;
                    }

                if($showCard) $image = $model->images->first()->medium_image_url ?? '/img/placeholder-card.svg';
            @endphp

            @if($showCard)
                @include('components.card', [
                    'cardLink' => $link,
                    'cardImage' => $image,
                    'cardTitle' => $model->translation->name,
                ])
            @endif
        @endforeach

    </div>
@endif