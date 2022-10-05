<ul class="c-vacancy-properties  @modifiers('c-vacancy-properties')">
	@foreach($properties as $key => $property)
		@continue(empty($property))
		<li class="c-vacancy-properties__item" title="{{__('vacancies.properties.'.$key)}}">
			<span class="c-vacancy-properties__icon">@include('components.icons.vacancy-property-'.$key)</span>
			<span class="c-vacancy-properties__label">{{$property}}</span>
		</li>
	@endforeach
</ul>