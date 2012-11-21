<?php
require '../../lastfmapi/lastfmapi.php';

/*
 * 
 * get song request with song title and artist
 * search for song metadata (api call)
 * check if streamable {
 *  go to url given by metadata
 *  retrieve youtube video source
 * } else {
 *  set workingStream = 0
 * }
 * 
 * return song object
 */

class LastFmCrawler {
    
    public function crawlTrack($track, $artist = "") {
        
        $auth = new lastfmApiAuth('setsession', $authVars);
        $apiFm = new lastfmApi();
        $trackClass = $apiClass->getPackage($auth, 'track', $config);

        // Setup the variables
        $methodVars = array(
	'track' => $track
        );

        $results = $trackClass->search($methodVars);
    }

}

?>
