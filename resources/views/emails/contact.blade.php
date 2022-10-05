@extends('emails.master')

@section('title')
    @lang('email.contact.subject')
@endsection

@section('content')

    <p>Hoi {{ config('site.company_name') }},</p>
    <p>Er is zojuist een nieuw contactverzoek binnengekomen:</p>

    <br/>

    {{-- Request information --}}
    @foreach($request as $key => $value)
        <p><strong>@lang('form.' . $key.'.label'): </strong><br/>
            {!! $value !!}
        </p>
    @endforeach

    <br/>
    <br/>

    @if(isset($request['email']))
        @include('emails.components.button', ['buttonLink' => 'mailto:'. $request['email'], 'buttonText' => 'Mail klant' ])
    @endif

    <p>&nbsp;</p>
    <p>
        Met vriendelijke groet,<br/>
        Team Komma
    </p>

@endsection


@section('layout')

    @include('emails.components.headerBar', ['headerBarTitle' => __('email.contact.subject')])

    @include('emails.components.body')

    @include('emails.components.footer', ['footerText' => '© ' . config('site.company_name')])

@endsection