{{-- If there is no video data (bug in components) continue --}}
@section('componentable-area-' . $loop->iteration)
    @if(!empty($component->video_video))

        <div class="l-contain">
            <div class="l-restrict" style="--max-columns: 8;">
                @include('components.video', [
                    'videoLink' => $component->video_video->get('video_id'),
                    'videoAutoplay' => $component->video_video->get('autoplay'),
                    'videoPlayerId' => 'componentable-area-' . $loop->iteration . '-video',
                ])
            </div>
        </div>

    @endif

@endsection

@include('organisms.componentables.componentableRow')

