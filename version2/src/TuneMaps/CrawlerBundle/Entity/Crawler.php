<?php

namespace TuneMaps\CrawlerBundle\Entity;

/**
 * An abstract crawler
 *
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
abstract class Crawler {
    
    /**
     * Retrieves the contents at given URI
     * 
     * @param string $uri The URI of the contents to retrieve
     */
    public function getExternalContents($uri) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$uri);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        $raw = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $raw;
    }
    
}
