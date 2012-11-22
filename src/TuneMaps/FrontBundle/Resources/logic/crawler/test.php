<?php
require 'CrawlLastFm.php';

if(!empty($_GET["track"])) {
	$track = $_GET["track"];
} else {
	$track = 'Yellow submarine';
}
if(!empty($_GET["artist"])) {
	$artist = $_GET["artist"];
} else {
	$artist = '';
}

$crawler = new CrawlLastFm();
$lastFmUrl = $crawler->searchTrack($track,$artist);
$youtubeURI = $crawler->getYoutubeUri($lastFmUrl);
if(!$youtubeURI) {
	echo "No track found!<br />";
} else {
	echo "Loaded: " . $_GET["track"] . " - " . $_GET["artist"] . "<br />";
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="http://www.youtube.com/iframe_api"></script>
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script>
	  function Player(id,element) { 
	  
		var iPlayer = new YT.Player(element, {
		  height: '200',
		  width: '200',
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
	  
	  function onYouTubeIframeAPIReady() {
		player = new Player('<?php echo $youtubeURI; ?>','player');
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
    </script>
</head>

<body>
	<input type="button" value="Pause" onclick="player.pauseTrack()" />
	<input type="button" value="Start" onclick="player.playTrack()" /><br />
	<input type="button" value="Next" onclick="player.loadVideoById('oABEGc8Dus0')" /><br />
	<div id="info"></div>
	<div id="footerContainer">
		<div id="topfooter">Video</div>
		<div id="footer"><div id="player"></div></div>
	</div>
</body>
</html>