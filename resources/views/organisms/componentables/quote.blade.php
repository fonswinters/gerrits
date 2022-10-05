@section('componentable-area-' . $loop->iteration)
    <div class="o-quote">

        <div class="o-quote__text">{!! $component->quote_text !!}</div>

        @if(!empty($component->quote_title || !empty($component->quote_subtitle)))
            <div class="o-quote__author">
                @if(!empty($component->quote_title))
                    <h3 class="o-quote__heading">{!! $component->quote_title !!}</h3>
                @endif
                @if(!empty($component->quote_subtitle))
                    <p class="o-quote__subheading">{!! $component->quote_subtitle !!}</p>
                @endif
            </div>
            @if(isset($component->quote_image) && $component->quote_image->count() != 0)
                <picture class="o-quote__picture">
                    <img class="o-quote__img"
                         src="{!!  $component->quote_image[0]->{'small_image_url'} !!}"
                         alt="" width="120" height="120">
                </picture>
            @endif
        @endif
    </div>

@endsection

@include('organisms.componentables.componentableRow')