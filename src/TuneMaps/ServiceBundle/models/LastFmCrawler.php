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
                $res[$key]->setDateTime(Date("j F Y, H:i ",strtotime($event->{'startDate'})));
                
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
                if(!$res[$key]->image = $event->{'image'}[2]->{'#text'}) {
                    $res[$key]->image = 'http://images.hacktabs.com//2012/07/404-not-found.gif';
                } 
                
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
                
                //build artist entity
                $artist = new Entity\Artist();
                $artist->setId($json->{'artist'}->{'mbid'});
                $artist->setName($json->{'artist'}->{'name'});
                
                //build song entity
                $track = new Entity\Song();
                $track->setId($json->{'mbid'});
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
        
        public function getUserInfo($username) {
            $url = $this->apiBaseUrl . "?method=user.getinfo&user=" . urlencode($username) . "&api_key=" . 
            urlencode($this->apiKey) . "&format=json";
            $json = json_decode($this->getUrl($url));

            if(!empty($json->{'error'})) {
                return false;
            }

            $json = $json->{'user'};
            $user = new \TuneMaps\UserBundle\Entity\User;
            $user->setGender($json->{'gender'});
            $user->setAge($json->{'age'});
            //$user->setLocation(null); //find location of country
            $user->setUsername($json->{'name'});
            $user->setUsernameCanonical($json->{'name'});
            $user->setPassword('pass_' . $json->{'name'});
            $user->setEmail('sample_' . $json->{'name'} . '@samples.tunemaps.com');
            $user->setEmailCanonical('sample_' . $json->{'name'} . '@samples.tunemaps.com');
            return $user;
        }
        
        public function getRecentTracks($username, $page = 1, $number = 100, $limit = 5) {
            if($page < 0) throw new Exception("Invalid page number.");
            $url = $this->apiBaseUrl . "?method=user.getRecentTracks&user=" . urlencode($username) . "&page=" . 
                    urlencode($page) . "&limit=" . urlencode($limit) . "&api_key=" . urlencode($this->apiKey) . "&format=json";
            $json = json_decode($this->getUrl($url));
            //check if page is out of bounds
/*            if(!empty($json->{'error'}) || $json->{'recenttracks'}->{'@attr'}->{'totalPages'} < $page) {
                return false;
            }*/
            
            if(empty($json->{'recenttracks'}->{'track'})) {
                return false;
            }
            
            //create SongPlayed objects
            $songs = array();
            $track = $json->{'recenttracks'}->{'track'};
            for($i = 0; $i < count($track); $i++) {
                if(empty($track[$i]->{'artist'}->{'mbid'}) || empty($track[$i]->{'mbid'})) continue;
                $songs[$i] = new Entity\SongPlayed($username,$track[$i]->{'mbid'});
                if(!empty($track->{'date'})) 
                    $songs[$i]->setLastPlayed(new \DateTime($track->{'date'}->{'uts'}));                
                else
                    $songs[$i]->setLastPlayed(new \DateTime());
            }
            return $songs;
        }
        
        public function getTopTracks($username, $page = 1, $number = 50) {
            if($page < 0) throw new Exception("Invalid page number.");
            $url = $this->apiBaseUrl . "?method=user.getTopTracks&user=" . urlencode($username) . "&page=" . 
                    urlencode($page) . "&limit=" . urlencode($limit) . "&api_key=" . urlencode($this->apiKey) . "&format=json";
            $json = json_decode($this->getUrl($url));
            
            //check if page is out of bounds
            if(!empty($json->{'error'}) || $json->{'recenttracks'}->{'@attr'}->{'totalPages'} < $page) {
                return false;
            }
            
            //create SongPlayed objects
            $songs = array();
            $track = $json->{'recenttracks'}->{'track'};
            for($i = 0; $i < count($track); $i++) {
                if(empty($track->{'artist'}->{'mbid'}) || empty($track->{'mbid'})) continue;
                $songs[$i] = new Entity\SongPlayed($track->{'artist'}->{'mbid'},$track->{'mbid'});
                if(!empty($track->{'date'})) 
                    $songs[$i]->setLastPlayed($track->{'date'}->{'uts'});                
                else
                    $songs[$i]->setLastPlayed(time());                
            }
            
            return $songs;
        }
        
        public function getActiveUsers($page) {
            if($page <= 0) throw new Exception("Invalid page number.");
            $url = 'http://www.last.fm/community/users/active?page=' . $page;
            $rawContents = $this->getUrl($url);
            preg_match_all("/<\/span> .+<\/a><\/strong>/",$rawContents,$users);
            $users = preg_replace("/<\/span> (.+)<\/a><\/strong>/", "$1",$users[0]);
            return $users; //array(string}
        }
}

/*
userid | songid | timesListened | lastListened
*/
?>