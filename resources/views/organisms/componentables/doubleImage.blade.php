@section('componentable-area-' . $loop->iteration)

    @include('organisms.doubleimage', [
        'images' => [
            $component->image_image_one->first(),
            $component->image_image_two->first()
        ],
        'imageSizes' => ['small' => '375', 'medium' => '575'],
        'isReversed' =>$component->image_reversed,
    ])

@endsection

@include('organisms.componentables.componentableRow')