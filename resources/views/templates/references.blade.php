@extends('master')

@section('title', $page->translation->meta_title != '' ? $page->translation->meta_title : $page->translation->name .' | '. config('site.company_name'))
@section('meta_description', $page->translation->meta_description)

@section('content')

    @include('components.pageTitle')

    @include('organisms.components')

    <div class="l-contain  o-block">
        @if(isset($references))
			@if($references->count() >= 1)
				{{-- References blocks --}}
				@include('organisms.references', [
					'references' => $references,
					'hideImages' => false
				])

{{--                @include('organisms.logos', [--}}
{{--                    'references' => $references,--}}
{{--                    'logosHeading' => __('references.heading'),--}}
{{--                ])--}}

			@else
				<div class="u-space-pt7  u-space-pb7">
					<p>{{__('references.no_references')}}</p>
				</div>
			@endif
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