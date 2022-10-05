{{-- TODO: When needed rewrite it to be compatible with form/layout/element like 'text' and 'textarea' --}}
<span id="{{$formGroupCodeName}}-error" class="error-message">@if($errors->has($formGroupCodeName)) {{ucfirst($errors->first($formGroupCodeName))}} @endif</span>

<div class="{{$formGroupCodeName}} form-group checkbox @if(isset($formGroupClasses)) {{$formGroupClasses}} @endif">
    <label for="{{$formGroupCodeName}}">
        <input class="checkbox__input" type="checkbox" id="{{$formGroupCodeName}}" name="{{$formGroupCodeName}}"  @if(\Illuminate\Support\Facades\Request::old($formGroupCodeName, '') !== '') checked @endif  @if(isset($duskSelector)) data-test="{{$duskSelector}}" @endif/>
        <span class="checkbox__text">@if(!isset($formGroupLabel))@lang('form.'.$formGroupCodeName.'.label')@else{!! $formGroupLabel !!}@endif</span>
    </label>
</div>