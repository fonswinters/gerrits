@php /** @var \Komma\KMS\Core\Attributes\DatePicker $attribute */ @endphp
<div class="entity-attribute  entity-attribute-time-picker  js-timepicker {!! $attribute->getStyleClass() !!}   @if($errors->has((string)$attribute->getKey())) error @endif {!!$attribute->getStyleClass()!!}"
     title="{{ $errors->get((string)$attribute->getKey()) != [] ? $errors->get((string)$attribute->getKey())[0] : ''}}"
     data-test="{{ $attribute->getKey() }}"
     data-hours-step="{{ $attribute->getHoursStep() }}"
     data-minutes-step="{{ $attribute->getMinutesStep() }}"
     data-key="{{ $attribute->getKey() }}"
     data-time-format="{{ $attribute->getTimeFormat() }}"
@foreach($attribute->getDataAttributes() as $dataAttributeName => $dataAttributeValue)
    {{ $dataAttributeName }}="{{ $dataAttributeValue }}"
    @endforeach
>

@include('KMS::attributes.label')

@if($attribute->getReadOnly())
    <div class="content">{!! $attribute->getValue() !!}</div>
@else

    <div class="time-field">
        <input
                id="{{$attribute->getKey()}}_time_hours"
                name="{{$attribute->getKey()}}_time_hours"
                    data-test="{{$attribute->getKey()}}_time_hours"
                type="number"
                readonly
                min="0"
                max="23"
        >
        <span>:</span>
        <input
                id="{{$attribute->getKey()}}_time_minutes"
                name="{{$attribute->getKey()}}_time_minutes"
                    data-test="{{$attribute->getKey()}}_time_minutes"
                type="number"
                readonly
                min="0"
                max="59"
        >
    </div>

    @if($attribute->getExplanation())<span class="explanation">{{ $attribute->getExplanation() }}</span> @endif
@endif
    <input type="hidden" name="{{$attribute->getKey()}}"
           id="{{$attribute->getKey()}}"
           value="{{$attribute->getValueAsJson()}}"
    >
</div>