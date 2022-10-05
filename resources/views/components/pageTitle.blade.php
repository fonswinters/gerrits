@php
    if(empty($model)) $model = !empty($page) ? $page : null;

    //When intro is not dark, set boolean 'isIntroDark' in config/site.php
    $isDark = config('site.isIntroDark') && (!empty($isInsideIntro));
@endphp


<div class="c-page-title {{$isDark ? 'on-dark' : ''}} @if(isset($pageTitleCenter)) c-page-title--center @endif">
    @if(!empty($backToLink) && !empty($backToLabel))
        <div class="c-page-title__preheading">
            @include('components.button', [
                'icon' => 'arrowLeft',
                'iconPos' => 'before',
                'modifiers' => ['text', $isDark ? 'on-dark' : ''],
                'buttonLink' => $backToLink,
                'buttonText' => $backToLabel,
            ])
        </div>
    @endif

    <h1 class="c-page-title__heading">
        {{!empty($heading) ? $heading : $model->translation->name }}
    </h1>

    @if(!empty($subHeading))
        <h2 class="c-page-title__subheading">{{$subHeading}}</h2>
    @endif

    @if(!empty($pageTitleDate))
        <div class="c-date-label  c-date-label--secondary  u-space-mt1">
            <span class="c-date-label__day">{{ $pageTitleDate->day }}</span>

            @unless($pageTitleDate->day != $pageTitleDate2->day && $pageTitleDate->month == $pageTitleDate2->month)
                <span class="c-date-label__month">@lang('calendar.month_names_short.'. ($pageTitleDate->format('n') - 1))</span>
            @endunless

            @if($pageTitleDate->format('d m') !== $pageTitleDate2->format('d m'))
                <span class="c-date-label__separator">t/m</span>
                <span class="c-date-label__day">{{ $pageTitleDate2->day }}</span>
                <span class="c-date-label__month">@lang('calendar.month_names_short.'. ($pageTitleDate2->format('n') - 1))</span>
            @endif
        </div>
    @endif
</div>