{{-- NOTE: This form will be send through ajax by the ChocolateFactory.js to prevent spam, the real post route is 'contact/process'. --}}
<form class="o-form  js-chocolate-factory" method="post" action="/contact/send" id="contactForm">

    @if( !\App::environment('production') ) {{debug($errors)}} @endif

    @include('components.form.honey')

    {{ csrf_field() }}
    <div class="o-form__header">
        <ul hidden class="c-form-feedback  js-error-area" data-feedback="@lang('contact.feedback_message')"></ul>
    </div>

    {{-- To prevent to pass the variable to each form element individually we already name it here --}}
    {{--@php $formGroupNoErrorArea = true @endphp--}}

    <div class="o-form__body">
        <div class="o-form__row">
            @include('components.form.text', ['formGroupCodeName' => 'name', 'formGroupNoErrorArea' => true])
        </div>
        <div class="o-form__row">
            @include('components.form.text', ['formGroupCodeName' => 'phone', 'formGroupNoErrorArea' => true])
        </div>
        <div class="o-form__row">
            @include('components.form.email', ['formGroupCodeName' => 'email', 'formGroupNoErrorArea' => true])
        </div>
        <div class="o-form__row">
            @include('components.form.textarea', ['formGroupCodeName' => 'form_message', 'formGroupNoErrorArea' => true])
        </div>
    </div>

    <div class="o-form__footer">

        {{-- NOTE: We don't use a button to prevent bots from clicking on it --}}
        <p class="c-button  js-golden-ticket">
            <span class="c-button__label">@lang('form.send')</span>
        </p>

    </div>
</form>