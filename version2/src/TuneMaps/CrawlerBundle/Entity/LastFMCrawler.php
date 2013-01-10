<?php

namespace TuneMaps\CrawlerBundle\Entity;

use TuneMaps\MusicDataBundle\Entity\Artist;
use TuneMaps\MusicDataBundle\Entity\Event;
use TuneMaps\MusicDataBundle\Entity\Location;
use TuneMaps\MusicDataBundle\Entity\Song;
use TuneMaps\MusicDataBundle\Entity\Venue;

/**
 * A crawler for Last FM
 */
class LastFMCrawler extends Crawler {
    
    /**
     * The API key
     * 
     * @var string
     */
    private $apiKey = 'dcd351ddc924b09be225a82db043311c';
    
    /**
     * The API base url
     * 
     * @var string
     */
    private $apiUrl = 'http://ws.audioscrobbler.com/2.0/';
    
    /**
     * Obtains the API url using given method and parameter string
     * 
     * @param string $method The last.FM method to call
     * @param array $parameters The parameters
     * @return string
     */
    protected function getUrl($method, $parameters) {
        $output = $this->apiUrl . '?method=' . $method;
        foreach($parameters as $name => $value) {
            $output .= '&' . $name . '=' . urlencode($value);
        }
        $output .= '&format=json&api_key=' . urlencode($this->apiKey);
        return $output;
    }
    
    /**
     * Searches for a track with given query
     * 
     * @param string $query The query
     * @param int $page The page number to start the search at
     * @param int $resultsPerPage The number of results per page
     */
    public function searchTrack($query, $page, $resultsPerPage) {
        
        // Create the API url
        $url = $this->getUrl('track.search', array('track' => $query, 'page' => $page, 'limit' => $resultsPerPage));
        $json = json_decode($this->getExternalContents($url));
        
        // fix different formats (1 = object, >1 = array)
        $result = $json->{'results'}->{'trackmatches'}->{'track'};
        if(!is_array($result)) {
            $result = array($result);
        }
        
        
        // Results
        $songs = array();
        if($result != null) {
            foreach($result as $track) {
                //create artist entity
                $artist = new Artist();
                $artist->setName($track->{'artist'});

                //create song
                $song = new Song();
                $song->setId($track->{'mbid'});
                $song->setArtist($artist);
                $song->setTitle($track->{'name'});
                $song->setYoutube(htmlspecialchars($track->{'artist'} . ' ' . $track->{'name'}, ENT_NOQUOTES));
                
                $image = '';
                if(array_key_exists('image', $track) && count($track->{'image'}) == 4) {
                    if(count($track->{'image'}[1]->{'#text'}) > 0) {
                        $image = $track->{'image'}[1]->{'#text'};
                    }
                }
                $song->setImage($image);
                
                $songs[] = $song;
            }
        }
        
        // Return the results
        return $songs;
        
    }
    // geeft de youtube uri terug van de video op een last.fm-pagina.
    // input: 	last.fm track-url (bijv. http://www.last.fm/music/Rudimental/_/Feel+The+Love+-+Feat.+John+Newman)
    // output: 	youtube video uri (bijv. oABEGc8Dus0)
    public function getYoutubeUri($lastFmUrl) {
            // load document
            $doc = new \DOMDocument();
            if(!@$doc->loadHTMLFile($lastFmUrl)) {
                    return false;
            }

            // search for movie param
            $params = $doc->getElementsByTagName('param');
            for($i = 0; $i < $params->length; $i++) {
                    $item = $params->item($i);
                    if($item->getAttribute('name') == 'movie') {
                        $url = current(explode('?',$item->getAttribute('value')));
                        $url = explode('/',$url);
                        return end($url);
                    }
            }
            return false;		
    }
    
    // required: mbid or (artist and track)
    public function trackInfo(array $args) {
        if(!empty($args['mbid']) || (!empty($args['track']) && !empty($args['artist']))) {
            $url = $this->getUrl('track.getInfo', $args);
            $json = json_decode($this->getExternalContents($url));

            // check if it is a valid result
            if(!empty($json->{'error'})) {
                return false;
            }
            $json = $json->{'track'};

            // build artist entity
            $artist = new Artist();
            $artist->setId($json->{'artist'}->{'mbid'});
            $artist->setName($json->{'artist'}->{'name'});

            // build song entity
            $track = new Song();
            $track->setId($json->{'mbid'});
            $track->setArtist($artist);
            $track->setTitle($json->{'name'});
            
            //set image
            if(!empty($json->{'album'}->{'image'})) {
                if(count($json->{'album'}->{'image'}[1]->{'#text'}) > 0) {
                    $track->setImage($json->{'album'}->{'image'}[1]->{'#text'});
                }
            }

            return $track;
        } else {
            return false;
        }
    }
    
    
    /**
     * Obtains all metros
     * 
     * @return array The metros
     */
    public function getMetros() {
        
        // Create the API url
        $url = $this->getUrl('geo.getmetros', array());
        
        // Return the results
        return json_decode($this->getExternalContents($url));
        
    }
    
    /**
     * Gets all events near a location
     * 
     * @param Location $location
     * @return array
     */
    public function getEvents($location) {
        
        // Create the API url
        $url = $this->getUrl('geo.getevents', array('long' => $location->getLongitude(), 'lat' => $location->getLatitude(), 'limit' => 100));
        
        // Create event objects
        $events = array();
        $json = json_decode($this->getExternalContents($url));
        foreach($json->{'events'}->{'event'} as $event) {
            $e = new Event();
            
            $venue = new Venue();
            $venue->setName($event->{'venue'}->{'name'});
            $location = new Location();
            $location->setLatitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:lat'});
            $location->setLongitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:long'});            
            $venue->setLocation($location);
            
            $e->setVenue($venue);
            $e->setName($event->{'title'});
            
            $attendingArtists = array();
            if(is_array($event->{'artists'}->{'artist'})) {
                foreach($event->{'artists'}->{'artist'} as $artist) {
                    $a = new Artist();
                    $a->setName($artist);
                    $attendingArtists[] = $a;
                }
            } else if(is_string($event->{'artists'}->{'artist'})) {
                $a = new Artist();
                $a->setName($event->{'artists'}->{'artist'});
                $attendingArtists[] = $a;
            }
            
            $e->setAttendingArtists($attendingArtists);
            $e->setDatetime($event->{'startDate'});
            $e->setId($event->{'id'});
            $events[] = $e;
        }
        
        // Return the results
        return $events;
        
    }
    
}
