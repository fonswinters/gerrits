<!DOCTYPE html>
<html lang="{{ \App::getLocale() ? \App::getLocale() : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>
        @section('title')
            {!! config('kms.site.title') !!}
        @show
    </title>
    <meta name="description" content="@yield('meta-description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base_url" content="{!! URL::to('/') !!}">

    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">

    @section('styles')
        <script src="{!! mix('js/kms/manifest.js') !!}"></script>
        <script src="{!! mix('js/kms/vendor.js') !!}"></script>
        <script src="{!! mix('js/kms/kms.js') !!}"></script>
        @stack('styles')
    @show

    @section('scripts')
        <link rel="stylesheet" href="{!! mix('/css/kms/kms.css') !!}">
        @stack('scripts')
    @show

</head>

<body>


@section('body')

@show
</body>
</html>