@extends('master')

@section('title', $service->translation->meta_title != '' ? $service->translation->meta_title : $service->translation->name .' | '. $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $service->translation->meta_title ?? '')
@section('meta_description', $service->translation->meta_description ?? '')

@section('content')

    <div class="o-block">
        @include('organisms.introContentImage', [
            'model' => $service,
            'backToLink' => $links->services->route,
            'backToLabel' => __('services.backToLabel'),
        ])
    </div>

    {{--When components are set on this page they will be put here --}}
    @include('organisms.components')

    {{-- Always show the services menu here after the components --}}
    <div class="o-block">
        <div class="o-nav-personal">
            <div class="o-nav-personal__s  s-text">
                <h2>{!! $servicePointHeading !!}</h2>
                @include('components.servicePoint', [
                    'buttonText' => $servicePointButton->translation->label ?? null,
                    'buttonLink' => $servicePointButton->translation->url ?? null,
                ])
            </div>
            <div class="o-nav-personal__n">
                @include('components.subnav', [
                    'menuName' => __('services.menuName'),
                    'models' => $services,
                    'modelTypeRoute' => $links->services->route,
                    'activeModelId' => $service->id,
                    'showBackToIndex' => true,
                    'backToLabel' => __('services.backToLabel'),
                ])
            </div>
        </div>
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
        {{--    @include('organisms.calloutBarWithServicePoint')--}}
        @include('organisms.calloutBar')
    </div>

@endsection