<?php

namespace TuneMaps\ServiceBundle\Models;

use TuneMaps\RecommendationBundle\Entity;

class LastFmCrawler extends AbstractCrawler {
	
	private $apiKey 	= 'dcd351ddc924b09be225a82db043311c';
	private $apiBaseUrl 	= 'http://ws.audioscrobbler.com/2.0/';
	
	// geeft de youtube uri terug van de video op een last.fm-pagina.
	// input: 	last.fm track-url (bijv. http://www.last.fm/music/Rudimental/_/Feel+The+Love+-+Feat.+John+Newman)
	// output: 	youtube video uri (bijv. oABEGc8Dus0)
	public function getYoutubeUri($lastFmUrl) {
		//load document
		$doc = new \DOMDocument();
		if(!@$doc->loadHTMLFile($lastFmUrl)) {
			return false;
		}
		
		//search for movie param
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
        
	public function searchTrack($track, $artist = "") {
		//create url
		$url = $this->apiBaseUrl . "?method=track.search&track=" . urlencode($track) . "&api_key=" . urlencode($this->apiKey) . "&format=json";
		if($artist != "") 
			$url .= "&artist=" . urlencode($artist);
		
		//get json
		$raw = $this->getUrl($url);
		
		//JSON
		$json = json_decode($raw);
		if(!empty($json->{'error'})) {
			return false;
		}
		
		$tracks = $json->{'results'}->{'trackmatches'};
		if(is_string($tracks)) {
                        return false;
		} else {
			return $tracks;
		}
	}
        
        public function getBestTrack($tracks) {
            if(is_string($tracks)) {
                return false;
            } elseif(is_array($tracks->{'track'})) {
                #loop door results op zoek naar streams;
                return $tracks->{'track'}[0];
            } else {
                return $tracks->{'track'};
            }
        }
	
        public function searchEvents(array $args) {
            $url = $this->apiBaseUrl . '?method=geo.getEvents&format=json&api_key=' . urlencode($this->apiKey);
            
            foreach($args as $key => $arg) {
                $url .= '&' . urlencode($key) . '=' . urlencode($arg);
            }
            
            $json = json_decode($this->getUrl($url));
            
            if(!empty($json->{'error'})) {
                return false;
            }
            
            //convert to local entities
            $res = array();
            foreach($json->{'events'}->{'event'} as $key=>$event) {
                //create Event
                $res[$key] = new Entity\Event;
                $res[$key]->setName($event->{'title'});
                $res[$key]->setId($event->{'id'});
                $res[$key]->setDateTime(date_parse($event->{'startDate'}));
                
                //create Artists
                $artists = $event->{'artists'}->{'artist'};
                if(is_string($artists))
                    $artists = array($artists);
                
                foreach($artists as $artist_name) {
                    $artist = new Entity\Artist();
                    $artist->setName($artist_name);
                    $res[$key]->addAttentingArtist($artist);
                }
                
                //create Venue
                $venue = new Entity\Venue($event->{'venue'}->{'id'});
                $venue->setName($event->{'venue'}->{'name'});
                
                //create Location
                $location = new Entity\Location;
                $location->setLattitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:lat'});
                $location->setLongitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:long'});
                
                //custom retrievals (not stored)
                $res[$key]->{'image'} = $event->{'image'}[2]->{'#text'};
                
                $venue->setLocation($location);
                $res[$key]->setVenue($venue);
            }
            
            return $res;
        }
        
        //required: mbid or (artist and track)
        public function trackInfo(array $args) {
            if(!empty($args['mbid']) || (!empty($args['track']) && !empty($args['artist']))) {
                $url = $this->apiBaseUrl . '?method=track.getInfo&format=json&api_key=' . urlencode($this->apiKey);
                foreach($args as $key=>$arg) {
                    $url .= '&' . $key . '=' . $arg;
                }
                $json = json_decode($this->getUrl($url));
                
                if(!empty($json->{'error'})) {
                    return false;
                }
                $json = $json->{'track'};
                $track = new Entity\Song();
                $track->setId($json->{'mbid'});
                $artist = new Entity\Artist();
                $artist->setId($json->{'artist'}->{'mbid'});
                $artist->setName($json->{'artist'}->{'name'});
                $track->setArtist($artist);
                $track->setTitle($json->{'name'});
		
                return $track;
            } else {
                return false;
            }
        }
	
	public function correctTrack($track,$artist) {
		$url = $this->apiBaseUrl . "?method=track.getcorrection&track=" . urlencode($track) . "&artist=" . urlencode($artist) . "&api_key=" . 
                        urlencode($this->apiKey) . "&format=json";
		$raw = $this->getUrl($url);
		$json = json_decode($raw);
		var_dump($json);
		if(!empty($json->{'error'}) || is_string($json->{'corrections'})) {
			return false;
		}
		
		$json = $json->{'corrections'}->{'correction'}->{'track'};
                $return = array();
		$return['track'] = $json->{'name'};
		$return['artist'] = $json->{'artist'}->{'name'};
		echo 'corrected search!';
		return $return;
	}
}

?>