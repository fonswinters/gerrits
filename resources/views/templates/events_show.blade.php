@extends('master', [ 'headerIsLight' => true ])

@section('title', $event->translation->meta_title != '' ? $event->translation->meta_title : $event->translation->name .' | '. $page->translation->name .' | '. config('site.company_name'))
@section('meta_title', $event->translation->meta_title ?? '')
@section('meta_description', $event->translation->meta_description ?? '')

@section('meta_information')

    @if(!empty(config('services.facebook.appId')))<meta property="fb:app_id" content="{{config('services.facebook.appId')}}"/>@endif
    <meta property="og:url" content="{{ \Request::root() . $_SERVER['REQUEST_URI'] }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title"
          content="{{$event->translation->meta_title }}"/>
    <meta property="og:description" content="{{$event->translation->meta_description}}"/>

    @if(isset($event->images) && $event->images->count() != 0)
        <meta property="og:image" content="{{\Request::root().$event->images->first()->medium_image_url}}"/>
    @endif
@endsection

@section('content')

    @if(!empty(config('services.facebook.appId')))
        {{-- Make a facebook app for this application --}}
        <script>
            window.fbAsyncInit = function () {
                FB.init({
                    appId: '{{config('services.facebook.appId')}}',
                    autoLogAppEvents: true,
                    xfbml: true,
                    version: 'v3.1'
                });
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    @endif

    @include('components.pageTitle', [
        'model' => $event,
        'backToLink' => $previousRoute,
        'backToLabel' => __('events.backToLabel'),
        'pageTitleDate' =>  $event->datetime_start,
        'pageTitleDate2' =>  $event->datetime_end,
    ])

    @include('organisms.components')


    <div class="o-block">
        <div class="o-event">
            <div class="o-event__data">
                <h2 class="o-event__heading">@lang('events.infoListHeading')</h2>
                <dl class="o-event__info-list">

                    @if(!empty($event->datetime_start))
                        <dt>@lang('events.when')</dt>
                        <dd>
                            <span>{{ $event->datetime_start->day }}</span>

                            @unless($event->datetime_start->day != $event->datetime_end->day && $event->datetime_start->month == $event->datetime_end->month)
                                <span>@lang('calendar.month_names.'. ($event->datetime_start->format('n') - 1)) {{$event->datetime_start->year}}</span>
                            @endunless

                            @if($event->datetime_start->format('d m') !== $event->datetime_end->format('d m'))
                                <span>t/m</span>
                                <span>{{ $event->datetime_end->day }} @lang('calendar.month_names.'. ($event->datetime_end->format('n') - 1)) {{$event->datetime_end->year}}</span>
                            @endif
                        </dd>
                    @endif

                    @if(!empty($event->datetime_start))
                        <dt>@lang('events.time')</dt>
                        <dd>Van <time>{{$event->datetime_start->format('H:i')}}</time>u tot <time>{{$event->datetime_end->format('H:i')}}</time>u</dd>
                    @endif

                    @if(!empty($event->translation->location))
                        <dt>@lang('events.location')</dt>
                        <dd>{{$event->translation->location}}</dd>
                    @endif

                    @if(!empty($event->translation->costs))
                        <dt>@lang('events.costs')</dt>
                        <dd>{{$event->translation->costs}}</dd>
                    @endif

                    @if(!empty($event->translation->description))
                        <dt>@lang('events.explanation')</dt>
                        <dd>{{$event->translation->description}}</dd>
                    @endif
                </dl>

                <h2 class="o-event__heading">@lang('events.servicePointHeading')</h2>

                @if(!empty($servicePointButton->translation->label) || !empty($servicePointButton->translation->url))
                    @include('components.servicePoint', [
                        'cardView' => false,
                    ])
                @endif

            </div>

            <div class="o-event__form  s-text">
                @if(isset($send))
                    <h2 class="">@lang('events.formSendHeading')</h2>
                    <p>@lang('events.formSendDescription')</p>
                @else
                    <h2 class="">@lang('events.formHeading')</h2>
                    @include('organisms.forms.signupForEvent')
                @endif
            </div>
        </div>
    </div>


    @if($nextEvents->count() >= 1)
        <div class="l-contain  o-block">
            @include('organisms.events', [
                'model' => $nextEvents
            ])
        </div>
    @endif

    <div class="o-block">
        @include('organisms.calloutBar')
    </div>

@endsection