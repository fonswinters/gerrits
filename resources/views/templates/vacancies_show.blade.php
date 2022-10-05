@php
	if(!isset($imageSizes)) $imageSizes = [ 'small' => 359, 'medium' => 724, 'large' => 1152];
	$image = $vacancy->hero->documents->first();
@endphp

@extends('master')

@section('title', $vacancy->translation->meta_title != '' ? $vacancy->translation->meta_title : $vacancy->translation->name .' | '. $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $vacancy->translation->meta_title ?? '')
@section('meta_description', $vacancy->translation->meta_description ?? '')

@section('content')

	@include('components.pageTitle', [
	   'model' => $vacancy,
	   'backToLink' => $links->vacancies->route,
	   'backToLabel' => __('vacancies.backToLabel'),
	   'subHeading' => $vacancy->translation->description
	])

	@if(!$vacancy->hero->documents->isEmpty())
		<div class="o-vacancy__hero">
			<picture>
				@foreach($imageSizes as $imageSizeKey => $imageSize)
					@if(!$loop->last)
						<source media="(max-width: {{$imageSize}}px)"
								srcset="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }}@endif">
					@else
						<img class="o-vacancy__hero-img" width="1152" height="448"
							 src="@if(isset($image->{$imageSizeKey.'_image_url'})){{ $image->{$imageSizeKey.'_image_url'} }} @elseif(isset($image[$imageSizeKey])){{ $image[$imageSizeKey] }} @endif"
							 alt="">
					@endif
				@endforeach
			</picture>
		</div>
	@endif


	<div class="o-vacancy__properties">
		@include('components.vacancyProperties', [
			'properties' => [
				'level' => $vacancy->translation->level,
				'experience' => $vacancy->translation->experience,
				'salary' => $vacancy->translation->salary,
				'hours' => $vacancy->translation->hours,
			]
		])
	</div>


	<div class="o-block  u-space-mt8">
		@if(!empty($vacancy->translation->hero_description))
			<div class="o-vacancy-intro">
				<div class="o-vacancy-intro__left  s-text">
					{!! $vacancy->translation->hero_description !!}
				</div>
				<div class="o-vacancy-intro__right">
					@if(!empty($servicePointButton->translation->label) || !empty($servicePointButton->translation->url))
						@include('components.servicePoint', [
							'cardView' => true,
							'buttonText' => __('vacancies.buttonToFormText') ?? 'Apply now',
							'buttonLink' => '#applyToVacancyForm',
							'buttonClasses' => 'js-scroll-to-target',
							'icon' => 'arrowDown'
						])
					@endif
				</div>
			</div>
		@endif
	</div>


	@include('organisms.components')
	@include('organisms.forms.vacancy')

@endsection