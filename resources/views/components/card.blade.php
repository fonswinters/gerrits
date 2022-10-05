<a class="c-card" href="{{$cardLink}}">
	@if(!empty($cardImage))
		<picture class="c-card__picture">
			<img class="c-card__img" src="{{ $cardImage }}" alt="" width="444" height="296"/>
		</picture>
	@endif

	@if(!empty($cardTitle))
		<p class="c-card__label">{{ $cardTitle }}</p>
	@endif

	<div class="c-card__arrow">
		@include('components.icons.arrowRight')
	</div>
</a>