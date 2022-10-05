@if(isset($components))

    @foreach($components as $component)
        @if(View::exists($component->view))
            @include($component->view)
        @else
            {{ debug('View not found: '. $component->view) }}
        @endif
    @endforeach

@endif