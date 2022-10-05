{{-- TODO: When needed rewrite it to be compatible with form/layout/element like 'text' and 'textarea' --}}

<span id="{{$formGroupCodeName}}-error" class="error-message">@if($errors->has($formGroupCodeName)) {{ucfirst($errors->first($formGroupCodeName))}} @endif</span>
<div class="{{$formGroupCodeName}} form-group select @if(isset($formGroupClasses)) {{$formGroupClasses}} @endif">
    <div class="select-wrapper">
        @if(!isset($formGroupNoLabel) || !$formGroupNoLabel)
            <label for="{{$formGroupCodeName}}">@lang('form.'.$formGroupCodeName.'.label')</label>
        @endif
        <span class="icon"></span>
        <select name="{{$formGroupCodeName}}" id="{{$formGroupCodeName}}">

            @if(isset($formGroupDisableNullOption) && $formGroupDisableNullOption)
                <option value="null" disabled="disabled"
                        @if(!empty($formGroupValue)) selected="selected" @endif >@lang('form.'.$formGroupCodeName.'.empty')</option>
            @endif

            @if(!empty($formGroupOptions))
                @foreach($formGroupOptions as $key => $value)
                    <option @if( (isset($formGroupValue) && $formGroupValue == $key) || \Illuminate\Support\Facades\Request::old($formGroupCodeName, '') == $key )  selected="selected" @endif value="{{$key}}">{{$value}}</option>
                @endforeach
            @endif

        </select>
    </div>
</div>