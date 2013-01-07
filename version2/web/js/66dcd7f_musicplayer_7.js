
/**
 * Youtube API
 */
var params = { allowScriptAccess: "always" };
var atts = { id: "youtube" };
var player = 0;
function onYouTubePlayerReady(id) {
    player = $("#youtube").get(0);
}

/**
 * Page load
 */
$(document).ready(function() {
    swfobject.embedSWF("http://www.youtube.com/apiplayer?enablejsapi=1", "youtube", "600", "200", "8", null, null, params, atts);
    $('div#playpause').click(function() {
        buttonPlayPause();
    })
});

/**
 * Pause/Play button
 */
function buttonPlayPause() {
    if(player.getPlayerState() == 0 || player.getPlayerState() == 2) {
        player.playVideo();
        $('#play').hide();
        $('#pause').show();
    } else {
        player.pauseVideo();
        $('#pause').hide();
        $('#play').show();
    }    
}

/**
 * Attempts to find a video of a song and play it
 */
function findSongAndPlay(artist, title) {
    $('#details').html('Loading...');
    $.ajax({
        url: $('#youtubecode').attr('action') + artist + '/' + title
    }).done(function(data) {
        if(data.youtube != "") {
            $('#details').html('<span class="title">' + data.title + '</span> - <span class="artist">' + data.artist + '</span>');
            player.loadVideoById(data.youtube);
            player.playVideo();
            $('#play').hide();
            $('#pause').show();
        } else {
            $('#details').html('<span class="error">Could not find playable stream</span>');
        }
    }).error(function(xhr, ajaxOptions, thrownError) {
        $('#details').html('<span class="error">Failed to load song</span>');
    });
}