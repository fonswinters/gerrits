{{--@unless( $component->grey_background == 1 ) {{dd($component)}} @endunless--}}

<div class="l-component  o-block" id="component-item-{{$loop->iteration}}">
    @yield('componentable-area-' . $loop->iteration)
</div>