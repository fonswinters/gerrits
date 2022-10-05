{{-- Create a default fall back for the image sizes --}}
@php
    // Sizes for projector
    if(!isset($imageSizes)) $imageSizes = [ 'small' => 425, 'medium' => 839];

    // Projector navigationMethods
    // Available; 0: dots 1: arrows 2: none
    $imageNavigationMethod = (isset($imageNavigationMethod)) ? $imageNavigationMethod : 1;

    // Projector auto-slide
    // Default auto-slide is disabled, because we don't like it...
    $imageAutoSlide = (isset($imageAutoSlide)) ? $imageAutoSlide : 0;

@endphp

@if(isset($images) && sizeof($images) >= 1)
    <div class="c-projector">

        <figure class="c-projector__figure  js-slider" id="{{$imageId}}" data-auto-slide="{{ $imageAutoSlide }}">

            @if(isset($caption) && !empty($caption) )
                <figcaption class="c-projector__caption  @if(!empty($captionReversed)) is-aligned-right @endif">
                    {{$caption}}
                </figcaption>
            @endif

            @foreach($images as $imageSlide)
                <picture class="c-projector__picture  js-slider-slide  @if($loop->iteration == 1) is-active @endif" data-order="{{$loop->index}}">
                    @foreach($imageSizes as $imageSizeKey => $imageSize)
                        @if(!$loop->last)
                            <source media="(max-width: {{$imageSize}}px)"
                                    srcset="@if(isset($imageSlide->{$imageSizeKey.'_image_url'})){{ $imageSlide->{$imageSizeKey.'_image_url'} }} @elseif(isset($imageSlide[$imageSizeKey])){{ $imageSlide[$imageSizeKey] }}@endif">
                        @else
                            <img class="c-projector__img"
                                 src="@if(isset($imageSlide->{$imageSizeKey.'_image_url'})){{ $imageSlide->{$imageSizeKey.'_image_url'} }} @elseif(isset($imageSlide[$imageSizeKey])){{ $imageSlide[$imageSizeKey] }} @else{{'https://via.placeholder.com/730x555'}} @endif"
                                 alt=""
                            >
                        @endif
                    @endforeach
                </picture>
            @endforeach


            @if(isset($images) && sizeof($images) > 1 && $imageNavigationMethod != 2)
                <div class="c-projector__control">
                    <div class="c-slide-indicator">
                        @switch($imageNavigationMethod)
                            @case(1)
                            <button class="c-slide-indicator__button  js-slider-button" aria-label="previous">@include('components.icons.arrowhead')</button>
                            <button class="c-slide-indicator__button  js-slider-button" aria-label="next">@include('components.icons.arrowhead')</button>
                            @break

                            @default
                            @foreach($images as $imageSlide)
                                <button class="c-slide-indicator__dot  js-slider-indicator  @if($loop->iteration == 1) is-active @endif" data-order="{{$loop->index}}"></button>
                            @endforeach
                            @break
                        @endswitch
                    </div>
                </div>
            @endif
        </figure>

    </div>
@else
    {{-- Fallback to placeholder, when no image is found --}}
    <div class="c-projector">
        <figure class="c-projector__figure">
            <picture class="c-projector__picture  is-active">
                <img class="c-projector__img" src="/img/placeholder-card.svg" alt="">
            </picture>
        </figure>
    </div>
@endif
