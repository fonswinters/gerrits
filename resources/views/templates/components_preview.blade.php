@extends('master')
@section('content')
    @if(isset($components))
        @foreach($components as $name => $component)
            <div class="o-block">
                <h2 class="c-heading  u-space-mb4">{{ $component->view }}</h2>
                {{-- Normally we check if the view exist, but for the sake of the test we don't to it here --}}
                @include($component->view)
            </div>
        @endforeach

    @endif
@endsection
