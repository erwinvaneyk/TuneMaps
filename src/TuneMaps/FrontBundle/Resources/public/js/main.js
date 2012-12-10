$(document).ready(function() {
	//set container dimensions
	setContainerDimensions();
        tracks = {};
        track = null;
	
	//music player
	function tick(){
		$('#player li:first').animate({'opacity':0}, 200, function () {$(this).appendTo($('#player ul')).css('opacity', 1);});
	}
	setInterval(function(){tick ()}, 4000);
	
	//handle search action
	$('.search form').submit(function(){ 
		$('#searchbar').attr('disabled', 'disabled');
		$('#searchbar').css('background', '#eee url("../ajax-loader.gif") no-repeat 5px 5px');
		getSearchResults(); 
		return false; 
	});
	
        $('#button_play').click(function(event) {
            console.log('play/pause button!: ' + player.getPlayerState());
            if(player.getPlayerState() == 1) {
                player.pauseTrack();
                $(this).text('play');
            } else {
                player.playTrack();
                $(this).text('pause');
            }
        });
        
        $('#button_next').click(function() {
            player.nextVideo();
        });
        
        $('#button_previous').click(function() {
            player.previousVideo();
        });
        console.log('loaded');
});

function onYouTubeIframeAPIReady() {
    player = new Player('','player');
}

//if window is resized then reset dimensions
$(window).resize(setContainerDimensions);

function setContainerDimensions(){
	$('#container').css('height', (window.innerHeight-50) + 'px');
	$('.bigmap').css('height', (window.innerHeight-50) + 'px');
	$('#map_canvas').css('width', (window.innerWidth-290) + 'px');
	return;
}

function getSearchResults(){
	//delete old results
	if ($('#menu ul li').hasClass('searchres')){
		$('#menu ul li').remove('.searchres');
		$('#menu ul li').remove('.track');
	}
	
	//display results
	animatedShow('#menu ul', 'label searchres', 'Search results', 1);
    var track = $('#searchbar').val();
	if (track != '') {
            $.ajax({
                url: 'service/tracks/' + track, //url
                success: function (response) {
                    if(!('error' in response)) {
                        tracks = response; 
                        if(!(tracks.track instanceof Array))
                            tracks.track = new Array(tracks.track);

                            if(tracks.length != 0){
                                for(i = 0; i < Math.min(5,tracks.track.length); i += 1) {
                                    var trackname = tracks.track[i].name;
                                    var artist = tracks.track[i].artist;
                                    var trackinfo = trackname + " by " + artist;
                                    var cover = '<img src="' + tracks.track[i].image[0]['#text'] + '" />';
                                    animatedShow('#menu ul', 'track',
                                                                '<a href="javascript:void(0);" onClick="ajaxLoadVideo(\'' + trackname + '\',\'' + artist + '\');">'
                                                                + trackinfo.substr(0,24) + cover + '</a>' ,1);
                                }
                            } else{
                                animatedShow('#menu ul', 'track', 'No results', 1);
                            }
                    } else
                        animatedShow('#menu ul', 'track', 'No results', 1);
                }
            });
    } else {
        animatedShow('#menu ul', 'track', 'No results', 1);
    }
}


function ajaxLoadVideo(track,artist) {
    var url = 'service/player/' + track;
    if(artist != '') {
        url = url + '/' + artist;
    }
    $.ajax({
        url: url,
        success: function (response) {
            console.log(player);
            player.loadVideoById(response.youtubeURI);
			
            if(!('error' in response))  {   
                console.log('video-id received. Retrieving video..');
                player.loadVideoById(response.youtubeURI);
            } else {
                console.log(response.error.description);
            }
			
        }
    });
}


function animatedShow(element, classtype, text, n){
	var newItem = $('<li class="' + classtype + '">' + text + '</li>').hide();
	$(element).append(newItem);
	if (n > 0)
		newItem.slideDown("500", function(){animatedShow(element, classtype, text, n-1)});
	else{
		//after loading re-enable search bar
		setTimeout(function(){
			$('#searchbar').removeAttr("disabled");
			$('#searchbar').css('background', '#fff url("../search.png") no-repeat 8px 7px');
		}, 1000);
	}
	return newItem;
}