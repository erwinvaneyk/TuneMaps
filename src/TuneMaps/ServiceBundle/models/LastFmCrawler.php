<?php

namespace TuneMaps\ServiceBundle\Models;

class LastFmCrawler {
	
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
				return $this->stripYoutubeURL($item->getAttribute('value'));
			}
		}
		return false;		
	}
	
	// haalt de video-URI uit een youtube-URL voor een bepaalde video
	public function stripYoutubeURL($url) {
		$url = current(explode('?',$url));
		$url = explode('/',$url);
		$url = end($url);
		return $url;
	}
	
	public function getUrl($url) {
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		$raw = curl_exec($curl_handle);
		curl_close($curl_handle);
		return $raw;
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
                $res[$key] = new Entity\Event;
                $res[$key]->setName($event->{'id'});
                $res[$key]->setDateTime(date_parse($event->{'startDate'}));
                $venue = new Entity\Venue($event->{'venue'}->{'id'});
                $location = new Entity\Location;
                $location->setLattitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:lat'});
                $location->setLongitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:long'});
                $venue->setLocation($location);
                $res[$key]->setVenue($venue);
            }
            
            return $res;
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
		$return['track'] = $json->{'name'};
		$return['artist'] = $json->{'artist'}->{'name'};
		echo 'corrected search!';
		return $return;
	}
}

?>
