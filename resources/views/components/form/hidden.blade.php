{{-- Note because it's hidden we don't need any wrapper arround --}}
<input type="hidden"
       id="{{$formGroupCodeName}}"
       name="{{$formGroupCodeName}}"
       data-test="{{$formGroupCodeName}}"
       value="@if(isset($formGroupValue)){{$formGroupValue}}@else{{\Illuminate\Support\Facades\Request::old($formGroupCodeName, '')}}@endif" />