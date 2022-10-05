@if(isset($vacancies) && count($vacancies) > 0)
	<ul class="o-vacancies">
		@foreach($vacancies as $key => $vacancy)
			@if(isset($vacancy->translation))
				<li class="o-vacancies__item">
					<a class="o-vacancies__link"
					   href="{{$links->vacancies->route . '/' . $vacancy->translation->slug}}">
						<div class="o-vacancies__content">
							<p class="o-vacancies__title">{{$vacancy->translation->name}}</p>

							@if(!empty($vacancy->translation->description))
								<p class="o-vacancies__subtitle">{{$vacancy->translation->description}}</p>
							@endif

							<div class="o-vacancies__properties">
								@include('components.vacancyProperties', [
    								'modifiers' => ['alt'],
									'properties' => [
										'level' => $vacancy->translation->level,
										'experience' => $vacancy->translation->experience,
										'salary' => $vacancy->translation->salary,
										'hours' => $vacancy->translation->hours,
									]
								])
							</div>
						</div>
						<span class="o-vacancies__icon">@include('components.icons.arrowRight')</span>
					</a>
				</li>
			@endif
			@if(!empty($amountOfVacancies))
				@break($amountOfVacancies == $key + 1)
			@endif
		@endforeach
	</ul>
@else
	<div>{{__('vacancies.noVacancies')}}</div>
@endif