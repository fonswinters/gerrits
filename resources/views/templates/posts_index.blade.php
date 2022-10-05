@extends('master', [ 'headerIsLight' => true ])

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_description', $page->translation->meta_description)

@section('content')

    @include('components.pageTitle')

    {{--When components are set on this page they will be put here --}}
    @include('organisms.components')

    <div class="o-news  o-block">
        @if($posts->count() >= 1)
            @include('organisms.news', [
                'model' => $posts
            ])

            @if (isset($posts) && $posts->hasPages())
                <div class="o-news__footer">
                    @include('components.pagination.list', ['paginator' => $posts])
                </div>
            @endif
        @else

            <h2>@lang('posts.no_posts')</h2>

        @endif
    </div>

    {{-- Discover row fixed on this page --}}
    @if(isset($discover_page_codenames) && count($discover_page_codenames) > 0)
        <div class="l-contain  o-block">
            @include('organisms.cardGrid', [
                'title' => __('global.discover_header'),
                'cardItems' => $discover_page_codenames,
                'modelThroughLinks' => true
            ])
        </div>
    @endif

    <div class="o-block">
        @include('organisms.calloutBarWithServicePoint')
    </div>

@endsection