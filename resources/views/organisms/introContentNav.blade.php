@component('organisms.intro')
	@slot('header')
		@include('components.pageTitle', ['isInsideIntro' => true])
	@endslot
	<div class="o-intro__content">
		<div class="o-intro__text  s-text">

			@if(!empty($page->translation->hero_title))
				<h2>{!! $page->translation->hero_title !!}</h2>
			@endif

			@if(!empty($page->translation->hero_description))
				<p>{!! $page->translation->hero_description !!}</p>
			@endif

			@includeWhen(!empty($page->hero->buttons),'components.button', [
			   'icon' => 'arrowRight',
			   'buttonText' => $page->hero->buttons->translation->label ?? '',
			   'buttonLink' => $page->hero->buttons->translation->url ?? '',
		   ])

		</div>
		<aside class="o-intro__nav">
			@include('components.subnav', [
				'menuName' => $menuName,
				'models' => $models,
				'modelTypeRoute' => $modelTypeRoute,
			])
		</aside>
	</div>
@endcomponent