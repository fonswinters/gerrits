@extends('emails.master')

@section('title', $subject)

@section('content')

	<p>@lang('email.eventSignUp.greeting') {{ $request['name'] }},</p>

	<br>


	<p>{!! __('email.eventSignUp.message', [
            'eventName' => $event->translation->name,
            'eventDate' => $event->datetime_start->day. ' ' . __('calendar.month_names.'. ($event->datetime_start->format('n') - 1)). ' ' . $event->datetime_start->year,
        ])!!}
		{!! __('email.eventSignUp.practical_information') !!}
	</p>

	<br>

	<table>
		@if(!empty($event->translation->location))
			<tr>
				<td class="table-label">
					@lang('email.eventSignUp.location'):
				</td>
				<td>
					{{ $event->translation->location }}
				</td>
			</tr>
		@endif

		<tr>
			<td class="table-label">
				@lang('email.eventSignUp.date'):
			</td>
			<td>
				{{ $event->datetime_start->day. ' ' . __('calendar.month_names.'. ($event->datetime_start->format('n') - 1)). ' ' . $event->datetime_start->year, }}

				@if($event->datetime_end)
					t/m {{ $event->datetime_end->day. ' ' . __('calendar.month_names.'. ($event->datetime_end->format('n') - 1)). ' ' . $event->datetime_end->year, }}
				@endif
			</td>
		</tr>

		@if(!empty($event->datetime_start) || !empty($event->datetime_end))
			<tr>
				<td class="table-label">
					@lang('email.eventSignUp.time'):
				</td>
				<td>
					Van {{ $event->datetime_start->format('H:i')}} tot {{ $event->datetime_end->format('H:i')}}
				</td>
			</tr>
		@endif
		@if(!empty($event->translation->costs))
			<tr>
				<td class="table-label">
					@lang('email.eventSignUp.costs'):
				</td>
				<td>
					{{ $event->translation->costs }}
				</td>
			</tr>
		@endif
		@if(!empty($event->translation->description))
			<tr>
				<td class="table-label">
					@lang('email.eventSignUp.description'):
				</td>
				<td>
					{{ $event->translation->description }}
				</td>
			</tr>
		@endif
	</table>

	<br>

	<p>
		@lang('email.eventSignUp.attachment_text') <br>
		@lang('email.eventSignUp.questions') @lang('email.eventSignUp.contact_us'): <a href="{{config('site.company_email')}}">{{config('site.company_email')
}}</a>
	</p>

	<br>

	<p>@lang('email.eventSignUp.closing_shout')</p>

	<br>

	<p>
		@lang('email.eventSignUp.closing_greet')<br/>
		@lang('email.eventSignUp.closing_team') {{ config('site.company_name') }}<br/>
		<a href="{{config('site.company_url')}}">{{config('site.company_url')}}</a>
	</p>

@endsection


@section('layout')

	@include('emails.components.headerBar', ['headerBarTitle' => $subject])

	@include('emails.components.body')

	@include('emails.components.footer', ['footerText' => '© ' . config('site.company_name')])

@endsection