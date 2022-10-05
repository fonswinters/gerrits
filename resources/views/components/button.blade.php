<{{ $tagName ?? 'a' }}
    class="c-button  @modifiers('c-button') {{ $buttonClasses ?? '' }} @if(!empty($icon)) has-icon @endif"
@if(isset($buttonLink))
    href="{{ $buttonLink }}"
@endif
@if(isset($isButtonTargetBlank) && $isButtonTargetBlank)
    target="_blank" rel="noopener noreferrer"
@endif
@if(isset($testKey))
    data-test="{{ $testKey }}"
@endif
@if(isset($dataAttributes))
    @foreach($dataAttributes as $dataAttribute => $value)
        {{ $dataAttribute }}={{ $value }}
    @endforeach
@endif
@if(!empty($iconPos)) data-icon-pos="{{$iconPos}}" @endif
@if(!empty($buttonIsFake)) data-button-is-fake="{{$buttonIsFake}}" @endif
>

<span class="c-button__label">{{ $buttonText ?? 'Button' }}</span>

@if(!empty($icon))
    <i class="c-button__icon @if(!empty($iconColor)) u-color-{{$iconColor}} @endif">
        @includeIf('components.icons.'.$icon)
    </i>
@endif

</{{ $tagName ?? 'a' }}>