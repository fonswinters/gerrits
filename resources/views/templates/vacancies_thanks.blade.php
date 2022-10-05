@extends('master')

@section('title', $vacancy->translation->meta_title != '' ? $vacancy->translation->meta_title : $vacancy->translation->name .' | '. config('site.company_name'))

@section('content')

	@include('components.pageTitle', [
    	'model' => $vacancy,
    	'heading' => __('vacancy.thanks.title'),
	])

	<div class="l-contain  u-space-mt6  u-space-mb4">
		<h2>@lang('vacancy.thanks.heading')</h2>
	</div>
	<div class="o-thanks">
		<div class="o-thanks__left">
			<ul class="c-toggle  js-toggle">
				<li class="c-toggle__item">
					<h2 class="c-toggle__title">
						<span class="c-toggle__icon  u-color-positive-500">@include('components.icons.check')</span>
						<span class="c-toggle__label">@lang('vacancy.thanks.you_applied')</span>
					</h2>
				</li>
				@foreach($vacancyProcess as $key => $step)
					<li class="c-toggle__item @if($loop->index === 0) is-active @endif  @if(!empty($step)) is-clickable @endif">
						<h2 class="c-toggle__title @if(!empty($step)) js-toggle-switch @endif">
							<span class="c-toggle__icon"></span>
							<span class="c-toggle__label">{{ $step->translation->name ?? '' }}</span>
						</h2>
						<div class="c-toggle__content  js-toggle-content">
							{!! $step->translation->description ?? '🤷🏻' !!}
						</div>
					</li>
				@endforeach
			</ul>
		</div>
		<div class="o-thanks__right">
			<h2 class="u-space-mb4">@lang('vacancy.thanks.heading_servicepoint')</h2>

			@include('components.servicePoint', [
				'cardView' => false,
			])
		</div>

	</div>


@endsection