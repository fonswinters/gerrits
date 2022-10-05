{{-- NOTE: This form will be send through ajax by the ChocolateFactory.js to prevent spam, the real post route is 'event/process'. --}}
<form class="o-form  js-chocolate-factory" method="post" action="/event/send" id="eventSignUpForm">

    @if( !\App::environment('production') ) {{debug($errors)}} @endif

    @include('components.form.honey', ['formId' => 'eventSignUpForm'])

    {{ csrf_field() }}
    <div class="o-form__header">
        <ul hidden class="c-form-feedback  js-error-area" data-feedback="@lang('events.formFeedbackMessage')"></ul>
    </div>

    {{-- To prevent to pass the variable to each form element individually we already name it here --}}
    @php $formGroupNoErrorArea = true @endphp

    <div class="o-form__body">
        @include('components.form.hidden', ['formGroupCodeName' => 'event', 'formGroupValue' => $event->id])

        <div class="o-form__row">
            @include('components.form.text', ['formGroupCodeName' => 'name'])
        </div>
        <div class="o-form__row">
            @include('components.form.email', ['formGroupCodeName' => 'email'])
        </div>
        <div class="o-form__row">
            @include('components.form.text', ['formGroupCodeName' => 'phone'])
        </div>
        <div class="o-form__row">
            @include('components.form.textarea', ['formGroupCodeName' => 'form_message'])
        </div>
    </div>

    <div class="o-form__footer">

        {{-- NOTE: We don't use a button to prevent bots from clicking on it --}}
        <p class="c-button  js-golden-ticket">
            <span class="c-button__label">@lang('form.send')</span>
        </p>

    </div>
</form>