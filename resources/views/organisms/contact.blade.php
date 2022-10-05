<div class="o-contact">
	<div class="o-contact__info">
		<h2 class="o-contact__header">{{config('site.company_name')}}</h2>

		<div class="o-contact__card">
			@include('components.servicePoint', [
				'button' => null,
				'cardView' => true
			])
		</div>

		<div class="o-contact__section">
			<h6 class="o-contact__heading">{{__('contact.data.address')}}:</h6>
			<p>{{config('site.company_address')}}</p>
			<p>{{config('site.company_zip')}} {{config('site.company_city')}}</p>
			<p>{{config('site.company_country')}}</p>
		</div>

		<div class="o-contact__section">
			<h6 class="o-contact__heading">{{__('contact.data.contact')}}:</h6>
			<p><a href="tel:{{ config('site.company_phone_call') }}">{{ config('site.company_phone_display') }}</a></p>
			<p><a href="mailto:{{ config('site.company_email') }}">{{ config('site.company_email') }}</a></p>
		</div>

		@if(!empty(config('site.company_bank')) || !empty(config('site.company_kvk')) || !empty(config('site.company_vat')))
			<div class="o-contact__section">
				<h6 class="o-contact__heading">{{__('contact.data.information')}}:</h6>
				<dl class="o-contact__numbers">
					@if(!empty(config('site.company_bank')))
						<dt>@lang('company.bank_number'):</dt>
						<dd>{{ config('site.company_bank') }}</dd>
					@endif
					@if(!empty(config('site.company_vat')))
						<dt>@lang('company.vat_number'):</dt>
						<dd>{{ config('site.company_vat') }}</dd>
					@endif
					@if(!empty(config('site.company_kvk')))
						<dt>@lang('company.kvk_number'):</dt>
						<dd>{{ config('site.company_kvk') }}</dd>
					@endif
				</dl>
			</div>
		@endif
	</div>
	<div class="o-contact__form">
		@if(isset($send))
			<h2 class="o-contact__header">@lang('contact.send.title')</h2>
			<p>@lang('contact.send.description')</p>
		@else
			<h2 class="o-contact__header">@lang('contact.form_title')</h2>
			@include('organisms.forms.contact')
		@endif
	</div>
</div>