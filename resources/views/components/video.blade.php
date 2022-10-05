@php $videoPlayerId = (isset($videoPlayerId) ? $videoPlayerId : 'random-video-' . mt_rand())  @endphp

<div class="c-video">
     <div class="c-video__iframe  js-youtube-player"
          @if(isset($videoLink)) data-youtube-link="{{ $videoLink }}" @endif
          data-auto-play="{{ $videoAutoplay ?? '' }}"
          id="{{ $videoPlayerId }}"
     >
     </div>
</div>