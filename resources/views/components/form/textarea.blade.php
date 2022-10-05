@extends('components.form.layout.element')

@section('form-group-area-' . $formGroupCodeName)

    {{-- To prevent space in the text editor we stick the value right after closing the textarea --}}
    <textarea class="c-input  js-form-input"
              id="{{$formGroupCodeName}}"
              name="{{$formGroupCodeName}}"
              data-test="{{$formGroupCodeName}}"
              {{--placeholder="@lang('form.'.$formGroupCodeName.'.placeholder')"--}}>@if(isset($formGroupValue)){{$formGroupValue}}@else{{\Illuminate\Support\Facades\Request::old($formGroupCodeName, '')}}@endif</textarea>

@endsection