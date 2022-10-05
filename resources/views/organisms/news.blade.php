@foreach($posts as $post)
    <article class="o-news__article">
        <a class="o-news__link  @if(isset($post->images) && $post->images->count() == 0) has-no-image @endif"
           href="{{$links->posts->route}}/{{$post->translation->slug}}">

            @if(isset($post->images) && $post->images->count() != 0)
                <img class="o-news__img" src="{{$post->images->first()->medium_image_url}}" width="600" height="600" alt=""/>
            @endif

            <div class="o-news__body">
                <h2 class="o-news__heading">{{ $post->translation->name }}</h2>

                @if($post->translation->short_description)
                    <p class="o-news__intro">
                        {{$post->translation->short_description}}
                    </p>
                @endif

                <time class="o-news__timestamp" datetime="{{$post->date->format('Y-m-d')}}">
                    {{$post->date->day}} @lang('calendar.month_names_short.'. ($post->date->format('n') - 1)) {{ $post->date->year }}
                </time>

                <p class="o-news__action">
                    <span class="o-news__readmore">Lees meer</span>
                    <span class="o-news__arrow">@include('components.icons.arrowRight')</span>
                </p>
            </div>
        </a>
    </article>
@endforeach