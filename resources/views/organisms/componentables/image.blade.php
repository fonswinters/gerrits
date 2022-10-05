@php
    if(!isset($imageSizes)) $imageSizes = [ 'small' => 442, 'medium' => 890, 'large' => 1360];
@endphp
@section('componentable-area-' . $loop->iteration)
    <div class="l-contain">

        @include('components.image', [
            'images' => $component->image_images,
            'imageId' => 'componentable-area-' . $loop->iteration.'-slider',
            'caption' => $component->image_caption,
            'imageNavigationMethod' => 1,
            'imageAutoSlide' => true
        ])

    </div>
@endsection

@include('organisms.componentables.componentableRow')