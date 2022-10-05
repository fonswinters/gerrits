
@if($sitemapItem->active)

    <li class="c-sitemap__item">
        <a class="c-sitemap__link" href="{{$links->{$sitemapItem->code_name}->route}}">{{$links->{$sitemapItem->code_name}->name}}</a>
    </li>

    {{-- Check if sitemap item is all the end of the tree branch --}}
    @unless($sitemapItem->rgt == $sitemapItem->lft+1)

        {{-- Check if the sitemap item has children --}}
        <?php $children = $sitemapItem->findChildren(); ?>
        @if(sizeof($children) != 0)

            {{-- If it does, recall this blade with the found child as item --}}
            <ul class="c-sitemap__sublist">
                @foreach($children as $child)
                    @include('components.sitemapLoop', ['sitemapItem' => $child])
                @endforeach
            </ul>
        @endif

    @endunless

@endif