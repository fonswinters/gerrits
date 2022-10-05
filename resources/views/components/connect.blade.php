@if(!empty($phoneDisplay) || !empty($phoneCall) || !empty($buttonText) || !empty($buttonLink))
    <div class="c-connect">
        @if(!empty($phoneDisplay) && !empty($phoneCall))
            <a class="c-connect__phone" href="tel:{!! $phoneCall !!}">@lang('global.call') {!! $phoneDisplay !!}</a>
        @endif

        @if(!empty($phoneDisplay) && !empty($phoneCall) && !empty($buttonText) && !empty($buttonLink))
            <span class="c-connect__or">@lang('global.or')</span>
        @endif

        @if(!empty($buttonText) && !empty($buttonLink))
            <span class="c-connect__action">
            @include('components.button', [
                'icon' => 'arrowRight',
                'buttonText' => $buttonText,
                'buttonLink' => $buttonLink,
            ])
        </span>
        @endif
    </div>
@endif