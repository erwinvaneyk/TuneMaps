<?php

namespace TuneMaps\ServiceBundle\Models;

abstract class AbstractCrawler {
    private $apiKey = '';
    private $apiBaseUrl= '';
    
    public function getUrl($url) {
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $raw = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $raw;
    }
    
    public function getBaseUrl() {
        return $this->apiBaseUri;
    }
}
?>
