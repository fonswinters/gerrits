@if(isset($page->heroVideo))
    @php
        if(!isset($imageSizes)) $imageSizes = [ 'small' => 839, 'medium' => 1151, 'large' => 1152];
        $images = $page->heroImage->documents;
        $caption = $page->heroImage->caption;
        $videoUrl = $page->heroVideo->url;
        $videoAutoplay = $page->heroVideo->autoplay;
    @endphp

    <div class="c-hero">
        <div class="c-hero__main  @if($images->count() > 1) js-slider @endif" id="hero-slider-{{$page->code_name}}">
            @if($videoUrl)
                @include('components.video', [
                    'videoLink' => $videoUrl,
                    'videoAutoplay' => $videoAutoplay,
                    'videoPlayerId' => 'home-hero-video',
                ])
            {{-- The slider contains 1 or more images --}}
            @elseif(isset($images) && $images->count() >= 1)
                <div class="c-hero__slider">
                    @foreach($images as $image)
                        <picture class="c-hero__picture  @if($images->count() > 1) js-slider-slide @else is-active @endif" data-order="{{$loop->index}}">
                            @foreach($imageSizes as $imageSizeKey => $imageSize)
                                @if(!$loop->last)
                                    <source media="(max-width: {{$imageSize}}px)"
                                            srcset="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }}@endif">
                                @else
                                    <img class="c-hero__image"
                                         src="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }} @endif"
                                         alt="">
                                @endif
                            @endforeach
                        </picture>
                    @endforeach
                </div>
            @else
            {{-- To indicate there are no hero images set show the placeholder image --}}
                <div class="c-hero__slider">
                    <picture class="c-hero__picture  is-active" style="width: 100%; height: 100%;">
                        <img class="c-hero__image" src="/img/placeholder-hero.svg" >
                    </picture>
                </div>
            @endif

            {{-- The controls are only visible if more than 1 image is set --}}
            @if(isset($images) && $images->count() > 1)
                <div class="c-hero__controls">
                    <div class="c-slide-indicator">
                        @foreach($images as $image)
                            <button class="c-slide-indicator__dot  js-slider-indicator  is-active" data-order="{{$loop->index}}"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- The caption is only shown when set--}}
            @if(!$videoUrl && !empty($caption))
                <div class="c-hero__caption">
                    <h2 class="c-hero__caption-inner">
                        {!! $caption !!}
                    </h2>
                </div>
            @endif
        </div>

        {{-- Scroll to button scrolls to the first component --}}
        <a class="c-hero__scroll  js-scroll-to-target" href="#component-item-1">
            @include('components.icons.arrowRight')
        </a>
    </div>
@endif