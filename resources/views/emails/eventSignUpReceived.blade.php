@extends('emails.master')

@section('title', $subject)

@section('content')

    <p>Hoi {{ config('site.company_name') }},</p>
    <p>Er is zojuist een nieuwe inschrijving binnengekomen voor een {{lcfirst(__('events.event'))}}:</p>


    <br/>
    <p>
        <strong>{{ucfirst(__('events.event'))}}: </strong><br/>
        {{ $event->translation->name }} (id: {{$event->id}})<br/>
    </p>


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

    @include('emails.components.headerBar', ['headerBarTitle' => $subject])

    @include('emails.components.body')

    @include('emails.components.footer', ['footerText' => '© ' . config('site.company_name')])

@endsection