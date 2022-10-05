<aside class="o-cookie-bar  is-accepted  js-cookie-bar">
    <div class="l-contain">
        <div class="o-cookie-bar__main">
            <p class="o-cookie-bar__text">{!! __('cookies.bar.message', ['link' => "<a class='o-cookie-bar__link' href='". $links->privacy->route ."'>" . __('cookies.bar.link') . "</a>" ])!!}</p>

            @include('components.button', [
                'isButton' => true,
                'modifiers' => ['ghost', 'on-dark', 'small'],
                'icon' => 'close',
                'buttonClasses' => 'o-cookie-bar__button  js-disable-cookie-bar',
                'buttonText' => __('cookies.bar.close')
            ])

        </div>
    </div>
</aside>