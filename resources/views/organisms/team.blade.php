@if(!empty($composedTeamMembers) && count($composedTeamMembers) > 0)
    <div class="o-team">
        <h2 class="c-heading  u-space-mb3">{{__('team.heading')}}</h2>
        <div class="o-team__matrix">
            @foreach($composedTeamMembers as $teamMember)
                <div class="o-team__item">

                    <div class="c-team-member">
                        <div class="c-team-member__photo">
                            <img class="c-team-member__img" src="{{$teamMember->documents->first()->medium_image_url ?? '/img/placeholder-person.svg'}}" alt="" width="300" height="300">
                        </div>
                        <div class="c-team-member__info">
                            <div class="c-team-member__heading">
                                @isset($teamMember->name)
                                    <h3 class="c-team-member__name">{{$teamMember->name ?? null}}</h3>
                                @endisset
                                @if(!empty($teamMember->linkedinurl))
                                    <a class="c-team-member__linkedin" href="{{$teamMember->linkedinurl}}" aria-label="{{$teamMember->linkedinurl ?? null}}">
                                        @include('components.icons.linkedin')
                                    </a>
                                @endif
                            </div>
                            @isset($teamMember->translation->function)
                                <p class="c-team-member__subheading">{{$teamMember->translation->function ?? null}}</p>
                            @endisset
                            @isset($teamMember->email)
                                <a class="c-team-member__link" href="mailto:{{$teamMember->email}}">{{$teamMember->email ?? null}}</a>
                            @endisset
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endif