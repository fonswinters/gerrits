/* ==========================================================================
    Youtube handler
 ========================================================================== */

const YoutubeHandler = {

    youtubeClass : '.js-youtube-player',
    players: [],

    init : function()
    {
        // Get the youtube players containers
        const youtubePlayers = document.querySelectorAll(YoutubeHandler.youtubeClass);
        const youtubePlayersAmount = youtubePlayers.length;

        for(let i = 0; i < youtubePlayersAmount; i++){

            const youtubePlayer = youtubePlayers[i];


            const youtubePlayerId = youtubePlayer.getAttribute('id');
            if(youtubePlayerId !== null )
            {
                // Strip the necessary data from the html and create objects from it
                const youtubeElement = {
                    id: youtubePlayer.getAttribute('id'),
                    link: youtubePlayer.getAttribute('data-youtube-link'),
                    autoPlay: parseInt(youtubePlayer.getAttribute('data-auto-play')),
                };

                YoutubeHandler.players.push(youtubeElement);
            }
            else{
                console.log("Element not include because there isn't a id on the player");
                console.log(youtubePlayer);
            }
        }

        if(youtubePlayersAmount >= 1) YoutubeHandler.initYoutube();

    },

    /**
     * Check if external script is loaded
     *
     */
    initYoutube: function() {
        // See if YT variable exists
        if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
            // Setup API ready function
            window.onYouTubePlayerAPIReady = function() {
                YoutubeHandler.loadPlayers();
            };
            // Load external script
            getScript('https://www.youtube.com/iframe_api');
            // If YT already exists load player
        } else {
            YoutubeHandler.loadPlayers();
        }
    },

    /**
     * Create the Youtube player(s) with parameters
     * And rewrite the players to key them by the element id
     *
     */
    loadPlayers: function() {

        let players = [];

        const youtubePlayersAmount = YoutubeHandler.players.length;
        for(let i = 0; i < youtubePlayersAmount; i++){

            let youtubePlayer = YoutubeHandler.players[i];

            // Load player
            youtubePlayer.player = new YT.Player(youtubePlayer.id,{
                height: 200,
                width: 200,
                videoId: youtubePlayer.link,
                host: 'https://www.youtube-nocookie.com',
                playerVars: {
                    modestbranding: 0,
                    rel: 0,
                    disablekb: 1,
                    autoplay: youtubePlayer.autoPlay
                },
                events: {
                    // 'onReady': YoutubeHandler.onReady,
                    'onStateChange': YoutubeHandler.onStateChange
                }
            });

            players[youtubePlayer.id] = youtubePlayer;

        }

        YoutubeHandler.players = players;

    },

    /**
     * When player is ready to play
     */
    onReady : function(event) {

        const playerContainerId = event.target.getIframe().getAttribute('id');
        const player = YoutubeHandler.players[playerContainerId].player;

        // Show video
        // setTimeout(function(){ $('#' + playerContainerId).stop().animate({ opacity: 1 },1000) },800);

        // If not on tablet or mobile, play on high quality
        // player.mute();
        // player.playVideo();
        // player.setPlaybackQuality('hd1080');
    },

    /**
     * Listener for Youtube state change
     */
    onStateChange : function(event) {

        const playerContainerId = event.target.getIframe().getAttribute('id');
        const player = YoutubeHandler.players[playerContainerId].player;

        const videoState = event.data;

        // Loop video
        if (event.data === YT.PlayerState.ENDED ) {
            player.playVideo();
        }
    }
};

YoutubeHandler.init();