@extends('components.form.layout.element')

@section('form-group-area-' . $formGroupCodeName)

    <input class="c-input  js-form-input"
           type="password"
           id="{{$formGroupCodeName}}"
           name="{{$formGroupCodeName}}"
           dusk="{{$formGroupCodeName}}"
           placeholder="@lang('form.'.$formGroupCodeName.'.placeholder')"
           value="" />

@endsection