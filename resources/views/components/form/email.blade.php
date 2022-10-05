@extends('components.form.layout.element')

@section('form-group-area-' . $formGroupCodeName)

    <input class="c-input  js-form-input"
           type="email"
           id="{{$formGroupCodeName}}"
           name="{{$formGroupCodeName}}"
           data-test="{{$formGroupCodeName}}"
{{--           placeholder="@lang('form.'.$formGroupCodeName.'.placeholder')"--}}
           value="@if(isset($formGroupValue)){{$formGroupValue}}@else{{\Illuminate\Support\Facades\Request::old($formGroupCodeName, '')}}@endif" />

@endsection