<?php
require 'lastFmApi/lastfmapi/lastfmapi.php';

class Crawler {
    
    public function crawlSong($song,$artist = "") {
        //search for song in the metadata api
        
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
