<!doctype html>
<html lang="{{ \App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0, viewport-fit=cover">

    @include('components.tracking.tagManagerHeader')

    @if(\App::environment() !== 'production')
        <meta name="robots" content="noindex"/>
    @endif


    <title>@yield('title', config('site.company_name'))</title>
    <meta name="title" content="@yield('meta_title', '')">
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('ogtags')
        @include('components.og_tags')
    @show

    {{-- This will prevent devices from automatically generating phone links --}}
    <meta name="format-detection" content="telephone=no">

    @if(!empty($languageMenu->withoutFallback))
        @foreach($languageMenu->withoutFallback as $iso => $metaOtherLanguage)
            <link rel="alternate" hreflang="{{ $iso }}"
                  href="{{ \URL::to($metaOtherLanguage) }}"/>
        @endforeach
    @endif

    <link href="{{ mix('css/style.css') }}" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    @include('components.favicons')

    @yield('meta_information')

</head>
<body @if(isset($page) && isset($page->code_name))id="{{$page->code_name}}" @endif class="{{ !empty($view) ?: '' }}">

@include('components.tracking.tagManagerBody')

@if(\App::environment() != 'production')
    @if(config('site.showGrid'))
        @include('components.visualGrid')
    @endif
@endif

@yield('base-content')

@if(!isset($hideCookieMessage) || !$hideCookieMessage)
    @include('components.cookie.cookies')
@endif


<script>
    @if(!empty(config('sentry.dsn')))
        window.sentry_dsn = '{{ config('sentry.dsn') }}';
    @endif
</script>

@include('components.richSnippets')

<script defer type="text/javascript" src="{{ mix('js/manifest.js') }}"></script>
<script defer type="text/javascript" src="{{ mix('js/vendor.js') }}"></script>
<script defer type="text/javascript" src="{{ mix('js/app.js') }}"></script>

</body>
</html>