<div class="o-double-image @if($isReversed) o-double-image--is-reversed @endif">
    @if(!empty($images))
        @foreach($images as $image)
            <picture class="o-double-image__picture">
            @if(isset($image))
                @foreach($imageSizes as $imageSizeKey => $imageSize)
                    @if(!$loop->last)
                        <source media="(max-width: {{$imageSize}}"
                            srcset="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }}@endif">
                    @else
                    <img class="o-double-image__img"
                         src="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }} @endif"
                         alt="">
                    @endif
                @endforeach
            @else
                <img class="c-projector__img" src="/img/placeholder-card.svg" alt="">
            @endif
            </picture>
        @endforeach
    @endif
</div>