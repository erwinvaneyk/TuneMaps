<?php

namespace TuneMaps\CrawlerBundle\Entity;

/**
 * A crawler for Youtube
 *
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
class YoutubeCrawler extends Crawler {
    
    /**
     * The API key
     * 
     * @var string
     */
    private $apiKey = 'AIzaSyBWMjX5wSD9Nrdvw73vOTXNHH34pr3yxw4';
    
    /**
     * The API base url
     * 
     * @var string
     */
    private $apiUrl = 'https://www.googleapis.com/youtube/v3/';
    
    /**
     * Gets the first video result of given query
     * 
     * @param string $query The query
     */
    public function getFirstVideo($query) {
        
        // Create the API url
        $url = $this->apiUrl . 'search?part=snippet&alt=json&q=' . urlencode($query) . '&key=' . urlencode($this->apiKey);
        
        // Obtain the results and return the first video if it exists
        $results = json_decode($this->getExternalContents($url));
        if($results->{'pageInfo'}->{'totalResults'} > 0) {
            return $results->{'items'}[0]->{'id'}->{'videoId'};
        }
        return null;
        
    }
    
}
