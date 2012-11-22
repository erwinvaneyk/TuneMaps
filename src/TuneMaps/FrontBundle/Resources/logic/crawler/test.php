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
	echo "Loaded: " . $track . " - " . $artist . "<br />";
}
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="http://www.youtube.com/iframe_api"></script>
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script>
    </script>
</head>

<body>
	<input type="button" value="Pause" onclick="player.pauseTrack()" />
	<input type="button" value="Start" onclick="player.playTrack()" /><br />
	<input type="button" value="Next" onclick="player.loadVideoById('oABEGc8Dus0')" /><br />
	<form action="" method="get">
		Track: <input type="text" name="track" /><br />
		Artist: <input type="text" name="artist" /><br />
		<input type="submit" value="listen" />
	</form>
	<div id="info"></div>
	<div id="footerContainer">
		<div id="topfooter">Video</div>
		<div id="footer"><div id="player"></div></div>
	</div>
</body>
</html>