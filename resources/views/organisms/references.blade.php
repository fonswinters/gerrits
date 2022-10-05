@php
    $hideImages = $hideImages ?? false;
@endphp

<div class="o-references">
    @foreach($references as $reference)
        @if(!empty($reference->translation))

            <div class="o-references__item">
                <div class="o-references__body">

                    @if(isset($reference->documents) && !$hideImages)
                        <picture class="o-references__picture">
                            <img class="o-references__img"
								 @if(isset($reference->documents) && $reference->documents->count() != 0)
                                    src="{!! $reference->documents->first()->medium_image_url !!}"
								 @else
                                    src="/img/placeholder-person.svg" style="width: 100%; height: 100%;"
								 @endif
                                    alt="">
                        </picture>
                    @endif

                    <div class="o-references__content">
                        @if(!empty($reference->translation->quote))
                            <div class="o-references__text">{!! $reference->translation->quote !!}</div>
                        @endif

                        @if(!empty($reference->translation->title || !empty($reference->translation->subtitle)))
                            <div class="o-references__author">
                                @if(!empty($reference->translation->title))
                                    <p class="o-references__heading">{!! $reference->translation->title !!}</p>
                                @endif
                                @if(!empty($reference->translation->subtitle))
                                    <p class="o-references__subheading">{!! $reference->translation->subtitle !!}</p>
                                @endif
                                @if(!empty($reference->translation->url))
                                    <a class="o-references__subheading" href="{{$reference->translation->url}}" target="_blank" rel="noopener noreferrer">{!! $reference->translation->url !!}</a>
                                @endif
                            </div>
                        @endif
                    </div>


                </div>
            </div>

        @endif
    @endforeach

</div>