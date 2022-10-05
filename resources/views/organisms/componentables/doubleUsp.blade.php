@section('componentable-area-' . $loop->iteration)

    <div class="o-double-usp">
        <div class="o-double-usp__main">
            <div class="o-double-usp__left">
                @if(isset($component->left["header"]) && $component->left["header"] != '')
                    <h2 class="o-double-usp__heading">{!! $component->left["header"] !!}</h2>
                @endif
                <ul class="o-double-usp__list">
                    @for($i = 0; $i < 5; $i++)
                        @if(!empty($component->left["items"][$i]))
                            <li class="o-double-usp__item">{!! $component->left["items"][$i] !!}</li>
                        @endif
                    @endfor
                </ul>
            </div>
            <div class="o-double-usp__right">
                @if(isset($component->left["header"]) && $component->right["header"] != '')
                    <h2 class="o-double-usp__heading">{!! $component->right["header"] !!}</h2>
                @endif
                <ul class="o-double-usp__list">
                    @for($i = 0; $i < 5; $i++)
                        @if(!empty($component->right["items"][$i]))
                            <li class="o-double-usp__item">{!! $component->right["items"][$i] !!}</li>
                        @endif
                    @endfor
                </ul>
            </div>
        </div>
    </div>

@endsection

@include('organisms.componentables.componentableRow')

