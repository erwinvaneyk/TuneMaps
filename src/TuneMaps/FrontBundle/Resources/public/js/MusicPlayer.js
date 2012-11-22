/*
function onYouTubeIframeAPIReady() {
    player = new Player(YOUTUBEURI HIER,'player');
}
 */
document.write("<script src=\"http://www.youtube.com/iframe_api\"></script>");

function Player(id,element) { 

    var iPlayer = new YT.Player(element, {
        height: '0',
        width: '0',
        videoId: id,
        playerVars: { 'autoplay': 1, 'controls':0, 'showinfo':0 },
        events: {
            //'onReady': onPlayerReady,
        }
    });

    iPlayer.stopTrack = function() {
            this.stopVideo();
    }

    iPlayer.pauseTrack = function() {
            this.pauseVideo();
    }

    iPlayer.playTrack = function() {
            this.playVideo();
    }

    iPlayer.getUrl = function() {
            return this.getVideoUrl();
    }

    return iPlayer;
}

$(document).ready(function(){
        $("#footer").hide();
        var bigFooter = false;
        $("#topfooter").click(function(event) {
                if(bigFooter) {
                        $("#footer").slideUp();
                        bigFooter = false;
                } else {
                        $("#footer").slideDown();
                        bigFooter = true;
                }			
        });
});

