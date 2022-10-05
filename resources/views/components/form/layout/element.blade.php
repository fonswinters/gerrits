<div class="{{$formGroupCodeName}} c-form-group @if(isset($formGroupClasses)) {{$formGroupClasses}} @endif  @if($errors->has($formGroupCodeName)) has-error @endif  js-form-group">

    @if(!isset($formGroupNoLabel) || !$formGroupNoLabel)
        <label class="c-form-group__label  js-form-label" for="{{$formGroupCodeName}}">@lang('form.'.$formGroupCodeName.'.label')</label>
    @endif

    <div class="c-form-group__field">
        @yield('form-group-area-' . $formGroupCodeName)
    </div>

    @unless(isset($formGroupNoErrorArea) && $formGroupNoErrorArea)
        <p class="c-form-group__error  js-form-group-error" id="{{$formGroupCodeName}}-error">@if($errors->has($formGroupCodeName)) {{ucfirst($errors->first($formGroupCodeName))}}@endif</p>
    @endunless

</div>

