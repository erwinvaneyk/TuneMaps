
/**
 * Youtube API
 */
var params = {allowScriptAccess: "always"};
var atts = {id: "youtube"};
var player = 0;
var recentTracks = new Array();
var nextTracks = new Array();
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
    });
    $('div#previous').click(function() {
        buttonPrevious();
    });
    $('div#next').click(function() {
        buttonNext();
    });
});

/**
 * Pause/Play button
 */
function buttonPlayPause() {
    if(player.getPlayerState() == 0 || player.getPlayerState() == 2) {
        nextTracks.pop();
        player.playVideo();
        $('#play').hide();
        $('#pause').show();
    } else {
        player.pauseVideo();
        $('#pause').hide();
        $('#play').show();
    }    
}

/*
 * finds and plays next song in the stack
 */
function buttonNext() {
    var track = nextTracks.pop();
    if(track != undefined)
        findSongAndPlay(track[0],track[1]);
}

/*
 * finds and plays previous song in the stack
 */
function buttonPrevious() {
    nextTracks.push(recentTracks.pop());
    var track = recentTracks.pop();
    if(track != undefined)
        findSongAndPlay(track[0],track[1]);
}

/**
 * Attempts to find a video of a song and play it
 */
function findSongAndPlay(artist, title) {
    $('#details').html('Loading...');
    var url = $('#youtubecode').attr('action') + artist + '/' + title;
    recentTracks.push([artist,title]);
    $.ajax({
        url: url
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