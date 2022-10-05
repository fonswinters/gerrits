@section('componentable-area-' . $loop->iteration)

	<div class="l-contain">

		@if(!empty($component->header))
			<h2 class="c-heading  u-space-mb5">{{$component->header}}</h2>
		@endif

		@include('organisms.vacancyList', [
			'amountOfVacancies' => $component->amount_of_vacancies,
		])

		@if(isset($component->button) && count($component->button) > 0 && $component->button[0]->translation)
			<div class="u-space-mt6">
				@include('components.button', [
					'icon' => 'arrowRight',
					'buttonText' => $component->button[0]->translation->label,
					'buttonLink' => $component->button[0]->translation->url
				])
			</div>
		@endif

	</div>

@endsection

@include('organisms.componentables.componentableRow')

