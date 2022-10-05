@extends('master')

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_description', $page->translation->meta_description)

@section('content')

    <div class="o-block">
        @include('organisms.introContentImage')
    </div>

    @include('organisms.components')

    @include('organisms.team')

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

    @include('organisms.calloutBar')

@endsection