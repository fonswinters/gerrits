@if(!empty($model) && count($model) > 0)
    <div class="o-events">
        <h2 class="c-heading  u-space-mb4">{{__('events.listHeading')}}</h2>
        <div class="o-events__main">
            @foreach($model as $event)
                <div class="o-events__item">

                    <a class="c-event-card" href="{{$links->events->route}}/{{$event->translation->slug}}">
                        <picture class="c-event-card__picture">
                            <img class="c-event-card__img" src="{{$event->documents->first()->medium_image_url ?? '/img/placeholder-event-card.svg'}}" alt="" width="264" height="280">
                            <div class="c-event-card__date">
                                <div class="c-date-label">
                                    <span class="c-date-label__day">{{ $event->datetime_start->day }}</span>

                                    @unless($event->datetime_start->day != $event->datetime_end->day && $event->datetime_start->month == $event->datetime_end->month)
                                        <span class="c-date-label__month">@lang('calendar.month_names_short.'. ($event->datetime_start->format('n') - 1))</span>
                                    @endif

                                    @if($event->datetime_start->format('d m') !== $event->datetime_end->format('d m'))
                                        <span class="c-date-label__separator">t/m</span>
                                        <span class="c-date-label__day">{{ $event->datetime_end->day }}</span>
                                        <span class="c-date-label__month">@lang('calendar.month_names_short.'. ($event->datetime_end->format('n') - 1))</span>
                                    @endif
                                </div>
                            </div>
                        </picture>
                        <div class="c-event-card__info">
                            @if(!empty($event->translation->name))
                                <h3 class="c-event-card__title">{{$event->translation->name}}</h3>
                            @endif
                            @if(!empty($event->datetime_start))
                                <p class="c-event-card__subtitle">
                                    {{$event->datetime_start->format('H:i')}}u
                                </p>
                            @endif
                            @if(!empty($event->translation->description))
                                <p class="c-event-card__type">{{$event->translation->description}}</p>
                            @endif
                        </div>
                    </a>

                </div>
            @endforeach
        </div>
    </div>
@endif