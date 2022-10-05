<nav class="c-subnav  @modifiers('c-subnav')" role="navigation">

    @if(!empty($menuName))
        <h2 class="c-subnav__header  c-heading">{{$menuName}}</h2>
    @endif

    <ul class="c-subnav__list">
        @if(isset($models) && $models->count() != 0)
            @foreach($models as $model)
                @if(!empty($model->translation->slug))
                    <li class="c-subnav__item @if(isset($activeModelId) && $model->id == $activeModelId) is-active @endif">
                        <a class="c-subnav__link" href="@if(isset($modelTypeRoute)){{$modelTypeRoute}}/@endif{{$model->translation->slug}}">
                            {{$model->translation->name}}
                            <span class="c-subnav__icon">
                                @include('components.icons.arrowRight')
                            </span>
                        </a>
                    </li>
                @endif
            @endforeach
        @else
            <li>
                <p>Models niet gedefineerd of is leeg</p>
            </li>
        @endif
    </ul>


    @if(isset($showBackToIndex) && $showBackToIndex && $backToLabel)
        <div class="c-subnav__back  u-space-mt3">
            @include('components.button', [
                'modifiers' => ['text'],
                'icon' => 'arrowLeft',
                'iconPos' => 'before',
                'buttonText' => $backToLabel,
                'buttonLink' => $modelTypeRoute
            ])
        </div>
    @endif

</nav>
