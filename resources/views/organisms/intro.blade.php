<div class="o-intro" @if(!empty($barOffset)) style="--bar-top-offset: {{$barOffset}}; @endif">
	@if(!empty($header))
		<div class="o-intro__header">
			{{$header}}
		</div>
	@endif
	<div class="o-intro__body">
		{{ $slot }}
	</div>
</div>