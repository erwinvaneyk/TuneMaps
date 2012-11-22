<?php
#todo json response
#todo more functions

require 'CrawlLastFm.php';

if(!empty($_GET['method'])) {
    $method = $_GET['method'];
    $crawler = new CrawlLastFm();
    if($method == 'getTrack') {
        if(!empty($_GET['track'])) {
            $track = $_GET['track'];
        } else {
            die('error 1');
        }
        $artist = '';
        if(!empty($_GET['artist'])) {
            $artist = $_GET['artist'];
        }
        
        $LastFmUrl = $crawler->searchTrack($track, $artist);
        $youtubeURI = $crawler->getYoutubeUri($LastFmUrl);
        die($youtubeURI);
    }
}



?>
