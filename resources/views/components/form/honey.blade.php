@if(old('honey', '') !== '')
    <p class="c-form-group__error">@lang('validation.bot_error')</p>
@endif
<script>

    var honeyInputHolder = document.createElement('DIV');
    honeyInputHolder.setAttribute('class', 'o-form__pot');

    var honeyInput = document.createElement('INPUT');
    honeyInput.setAttribute('name', '_honey');
    honeyInput.setAttribute('type', 'text');
    honeyInput.setAttribute('autocomplete', 'honeyPot');
    honeyInput.setAttribute('value', '{{old('honey', '')}}');

    honeyInputHolder.appendChild(honeyInput);

    var form = document.getElementById('{{$formId ?? 'contactForm'}}');
    form.insertBefore(honeyInputHolder, form.querySelector('*:first-child'));

</script>
<noscript>
    Please activate your javascript to fill in this contact form.
</noscript>