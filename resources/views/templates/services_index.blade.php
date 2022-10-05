@extends('master')

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_description', $page->translation->meta_description)

@section('content')

    <div class="o-block">
        @include('organisms.introContentNav', [
            'menuName' => __('services.menuName'),
            'models' => $services,
            'modelTypeRoute' => $links->services->route,
        ])
    </div>

    {{--When components are set on this page they will be put here --}}
    @include('organisms.components')

    <div class="l-contain  o-block">
        @if($services && $services->count() > 0)
            @include('organisms.cardGrid', [
                'title' => __('services.heading'),
                'cardItems' => $services,
                'modelTypeRoute' => $links->services->route,
            ])
        @else
            <h2>{{__('services.noServices')}}</h2>
        @endif
    </div>

    <div class="o-block">
        @include('organisms.calloutBarWithServicePoint')
    </div>

@endsection