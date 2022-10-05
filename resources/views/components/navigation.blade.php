<nav class="c-nav" role="navigation">
    <ul class="c-nav__list">
        @foreach($navItems as $navKey => $navItem)
            @continue(empty($links->{$navItem->code_name}))
            <li class="c-nav__item">
                <a class="c-nav__link @if(isset($page->code_name) && $page->code_name == $navItem->code_name) is-active @endif" href="{{ $links->{$navItem->code_name}->route }}" @if(!empty($isSticky)) tabindex="-1" @endif>
                    {{ $links->{$navItem->code_name}->name }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
