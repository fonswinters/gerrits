@php /** @var \App\Pages\Models\Page $page */@endphp
@extends('master', [ 'headerIsLight' => true ])

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $page->translation->meta_title ?? '')
@section('meta_description', $page->translation->meta_description ?? '')

@section('content')

    <div class="o-block">
        @component('organisms.intro', ['barOffset' => '100%'])
            @include('components.hero')
        @endcomponent
    </div>

    @include('organisms.components')

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
        @include('organisms.calloutBar')
    </div>

@endsection
