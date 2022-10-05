@extends('master')

@section('title', $project->translation->meta_title != '' ? $project->translation->meta_title : $project->translation->name .' | '. $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $project->translation->meta_title ?? '')
@section('meta_description', $project->translation->meta_description ?? '')

@section('content')

    <div class="o-block">
        @include('organisms.introContentImage', [
            'model' => $project,
            'backToLink' => $links->projects->route,
            'backToLabel' => __('projects.backToLabel'),
        ])
    </div>

    {{--When components are set on this page they will be put here --}}
    @include('organisms.components')


    {{-- Always show the projects menu here after the components --}}
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
                    'menuName' => __('projects.menuName'),
                    'models' => $projects,
                    'modelTypeRoute' => $links->projects->route,
                    'activeModelId' => $project->id,
                    'showBackToIndex' => false,
                    'backToLabel' => __('projects.backToLabel'),
                ])
            </div>
        </div>
    </div>

    {{-- Discover row fixed on this page --}}
    <div class="l-contain  o-block">
        @if(isset($discover_page_codenames) && count($discover_page_codenames) > 0)
            @include('organisms.cardGrid', [
                'title' => __('projects.heading'),
                'cardItems' => $discover_page_codenames,
                'modelThroughLinks' => true
            ])
        @endif
    </div>

    <div class="o-block">
        {{--    @include('organisms.calloutBarWithServicePoint')--}}
        @include('organisms.calloutBar')
    </div>

@endsection