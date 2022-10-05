@php
    $formId = 'quotationForm';
@endphp

<div class="o-apply">
    <div class="o-apply__main">
        <form class="o-apply__form js-chocolate-factory" method="post" action="{{url("/vacancy/send")}}" id="{{$formId}}">

            <input type="hidden" name="vacancy" value="{{ $vacancy->translation->name }}">
            <input type="hidden" name="vacancy_id" value="{{$vacancy->id}}">

            @if( !\App::environment('production') ) {{debug($errors)}} @endif

            @include('components.form.honey', ['formId' => $formId])

            {{ csrf_field() }}


            @if(session()->get('send', false))
                <h2 class="o-apply__heading">@lang('vacancy.send.title')</h2>
                <div class="o-apply__body">
                    <p>@lang('vacancy.send.description')</p>
                </div>
            @else
                <h2 class="o-apply__heading">{{__('vacancies.formHeading')}}</h2>
                <div class="o-apply__body">
                    <div class="o-apply__left">
                        <div class="o-apply__row">
                            @include('components.form.text', ['formGroupCodeName' => 'name', 'formGroupNoErrorArea' => true])
                        </div>
                        <div class="o-apply__row">
                            @include('components.form.email', ['formGroupCodeName' => 'email', 'formGroupNoErrorArea' => true])
                        </div>
                        <div class="o-apply__row">
                            @include('components.form.text', ['formGroupCodeName' => 'phone', 'formGroupNoErrorArea' => true])
                        </div>
                        <div class="o-apply__row  o-apply__fileupload">
                            @include('components.form.fileUpload', ['formGroupCodeName' => 'files', 'formGroupNoErrorArea' => true])
                        </div>
                    </div>

                    <div class="o-apply__right">
                        @include('components.form.textarea', ['formGroupCodeName' => 'motivation', 'formGroupNoErrorArea' => true])
                    </div>
                </div>

                <div class="o-apply__feedback">
                    <ul hidden class="c-form-feedback  js-error-area" data-feedback="@lang('vacancy.feedback_message')"></ul>
                </div>

                <div class="o-apply__footer">
                    <div class="c-button js-golden-ticket">
                        <span class="c-button__label">@lang('form.send')</span>
                    </div>
                </div>
            @endif

        </form>
    </div>
</div>