@extends('base')

@section('base-content')

{{--    @if(!empty(config('site.company_phone_display')))--}}
{{--        @include('organisms.topBar')--}}
{{--    @endif--}}

    @include('organisms.header')
    @include('organisms.header', ['isSticky' => true])
    @include('organisms.overlayMenu')

    <main class="o-body" id="main" role="main">
        @yield('content')
    </main>

    @include('organisms.footer')

@endsection