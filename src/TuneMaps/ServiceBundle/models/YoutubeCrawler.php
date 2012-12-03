<?php

namespace TuneMaps\ServiceBundle\Models;

class YoutubeCrawler {
    private $apiKey = 'AIzaSyBWMjX5wSD9Nrdvw73vOTXNHH34pr3yxw4';
    private $apiBaseUrl = 'https://www.googleapis.com/youtube/v3/';
    
    //returns array of youtube-id's
    public function searchTracks($track, $artist = '') {
        $url = $this->apiBaseUrl . 'search?part=snippet&alt=json&key=' . $this->apiKey . '&q=' . urlencode($track . ' ' . $artist);
        $json = json_decode($this->getUrl($url));

        if($json->{'pageInfo'}->{'totalResults'} == 0) {
            return false;
        }
        
        $res = array();
        $track = $json->{'items'};
        foreach($track as $item) {
            if(!empty($item->{'id'}->{'videoId'}))
                $res[] = $item->{'id'}->{'videoId'};
        }
        return $res;
    }
    
    public function getUrl($url) {
            $curl_handle=curl_init();
            curl_setopt($curl_handle, CURLOPT_URL,$url);
            curl_setopt ($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
            $raw = curl_exec($curl_handle);
            curl_close($curl_handle);
            return $raw;
    }
	
}

?>
