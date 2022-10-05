<div class="o-logos">
    @if(isset($logosHeading) && $logosHeading != '')
        <h2 class="c-heading">{{ $logosHeading }}</h2>
    @endif
    <ul class="o-logos__list">

        @foreach($references as $reference)
            @if(!empty($reference->documents->first()))

                <li class="o-logos__item">
                    @if(!empty($reference->translation->url))
                        <a class="o-logos__link" href="{{!empty($reference->translation->url) ? $reference->translation->url : 'javascript: void(0);'}}" target="_blank" rel="noopener noreferrer">
                    @endif

                    <figure class="o-logos__img" style="background-image: url('{!! $reference->documents->first()->medium_image_url !!}');"></figure>

                    @if(!empty($reference->translation->url))
                        </a>
                    @endif
                </li>
            @endif
        @endforeach

    </ul>
</div>