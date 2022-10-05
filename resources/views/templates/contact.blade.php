@extends('master', [ 'headerIsLight' => true ])

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_description', $page->translation->meta_description)

@section('content')

    <div class="o-block">
        @component('organisms.intro')
            @slot('header')
                @include('components.pageTitle', ['isInsideIntro' => true])
            @endslot
            @include('organisms.contact')
        @endcomponent
    </div>

    @include('organisms.components')

    <div class="o-block">
        <div class="l-contain">
            <h3 class="c-heading  u-space-mb3">@lang('contact.map_heading')</h3>
            <div class="o-map  js-google-map" id="map" style="height: 576px; max-height: 50vh"
                 data-google-lat="{{ !empty(config('site.google_maps_lat')) ? config('site.google_maps_lat') : '51.257929' }}"
                 data-google-lng="{{ !empty(config('site.google_maps_long')) ? config('site.google_maps_long') : '5.595330' }}"
            ></div>
        </div>
    </div>

    <div class="o-block">
        @include('organisms.calloutBar')
    </div>

@endsection