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
	$('#container').css('width', (document.documentElement.offsetWidth-200) + 'px');
	$('#container').css('height', (window.innerHeight-50) + 'px');
	$('.bigmap').css('height', (window.innerHeight-50) + 'px');
	return;
}

function getSearchResults(){
	//delete old results
	if ($('#menu ul li').hasClass('searchres')){
		$('#menu ul li').remove('.searchres');
		$('#menu ul li').remove('.track');
	}
	
	/* >> ajax call here <<
	 * show spinner while loading
	 * do not allow users to send request twice
	 */
        
	//display results
	animatedShow('#menu ul', 'label searchres', 'Search results', 1);
        var track = $('#searchbar').val();
	if (track != '') {
            $.ajax({
                url: 'tracks/' + track, //url
                success: function (response) {
                    if(response != '') {   
                        tracks = response; 
                        if(!(tracks.track instanceof Array))
                            tracks.track = new Array(tracks.track);
                        
                        for(var i = 0; i < Math.min(5,tracks.track.length); i += 1) {
                            animatedShow('#menu ul', 'track','<a class="test" href="javascript:void(0);" onClick="ajaxLoadVideo(tracks.track[' + i +  '].name,tracks.track[' + i + '].artist);">' + tracks.track[i].name + " by "+ tracks.track[i].artist + '</a>',1);
                        }
                    } else {
                        animatedShow('#menu ul', 'track', 'No results', 1);
                    }
                }
            });
        } else {
            animatedShow('#menu ul', 'track', 'No results', 1);
        }
}


function ajaxLoadVideo(track,artist) {
    var url = 'player/' + track;
    if(artist != '') {
        url = url + '/' + artist;
    }
    $.ajax({
        url: url, //url
        success: function (response) {
            console.log(player);
            player.loadVideoById(response.youtubeURI);
        }
    });
}


function animatedShow(element, classtype, text, n){
	var newItem = $('<li class="' + classtype + '">' + text + '</li>').hide();
	$(element).append(newItem);
	if (n > 0)
		newItem.slideDown("500", function(){animatedShow(element, classtype, text, n-1)});
	return newItem;
}