@extends('master', [ 'headerIsLight' => true ])

@section('title', $post->translation->meta_title != '' ? $post->translation->meta_title : $post->translation->name .' | '. $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $post->translation->meta_title ?? '')
@section('meta_description', $post->translation->meta_description ?? '')

@section('meta_information')

    @if(!empty(config('services.facebook.appId')))<meta property="fb:app_id" content="{{config('services.facebook.appId')}}"/>@endif
    <meta property="og:url" content="{{ \Request::root() . $_SERVER['REQUEST_URI'] }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title"
          content="{{$post->translation->meta_title }}"/>
    <meta property="og:description" content="{{$post->translation->meta_description}}"/>

    @section('ogtags')
        <meta property="og:title" content="{{ $post->translation->meta_title != '' ? $post->translation->meta_title : $post->translation->name  }}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="{{ request()->root() . request()->server("PATH_INFO", '') }}" />
        <meta property="og:image" content="{{ isset($post->images) && count($post->images) > 0 ? url((String)$post->images->first()->medium_image_url) : url('/img/open-graph-image.jpg') }}" />
        <meta property="fb:app_id" content="{{config('services.facebook.appId')}}"/>
        <meta property="og:description" content="{{$post->translation->description}}"/>
    @stop
@endsection

@section('content')

    @if(!empty(config('services.facebook.appId')))
        {{-- Make a facebook app for this application --}}
        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '{{config('services.facebook.appId')}}',
                    autoLogAppEvents: true,
                    xfbml: true,
                    version: 'v3.1'
                });
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    @endif

    @include('components.pageTitle', [
        'model' => $post,
        'backToLink' => $previousRoute,
        'backToLabel' => __('posts.backToLabel'),
        'pageTitleCenter' => true
    ])

    @include('organisms.components')


    <div class="l-contain  o-block">
        <div class="l-restrict" style="--max-columns: 6">
            @include('components.subnav', [
                'modifiers' => ['boxed'],
                'menuName' => __('posts.menuName'),
                'models' => $latestPosts,
                'modelTypeRoute' => $links->posts->route,
                'activeModelId' => $post->id,
                'showBackToIndex' => false,
                'backToLabel' => __('posts.backToLabel'),
            ])
        </div>
    </div>

    <div class="o-block">
        @include('organisms.calloutBarWithServicePoint')
    </div>

@endsection