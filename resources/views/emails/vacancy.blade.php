@extends('emails.master')

@section('title')
    @lang('email.vacancy.subject')
@endsection

@section('content')

    <p>Hoi {{ $mailToName ?? config('site.company_name') }},</p>
    <p>Er is zojuist een nieuwe sollicitatie binnengekomen:</p>

    <br/>

    {{-- Request information --}}
    @foreach($request as $key => $value)

        {{-- We don't show vacancy ID in the mail --}}
        @continue($key == 'vacancy_id')

        <p><strong>@lang('form.' . $key.'.label'): </strong><br/>
            {!! $value !!}
        </p>
    @endforeach

    <br/>
    <br/>

    @if(isset($request['email']))
        @include('emails.components.button', ['buttonLink' => 'mailto:'. $request['email'], 'buttonText' => 'Mail sollicitant' ])
    @endif

    <p>&nbsp;</p>
    <p>
        Met vriendelijke groet,<br/>
        Team Komma
    </p>

@endsection


@section('layout')

    @include('emails.components.headerBar', ['headerBarTitle' => __('email.vacancy.subject')])

    @include('emails.components.body')

    @include('emails.components.footer', ['footerText' => '© ' . config('site.company_name')])

@endsection