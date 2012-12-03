<?php

namespace TuneMaps\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TuneMaps\RecommendationBundle\Entity;


class PlayerController extends Controller
{
    public function artistAction(Request $request, $track, $artist)
    {
            return $this->renderPlayer($track,$artist);
    }
	
    public function trackAction(Request $request, $track)
    {
            return $this->renderPlayer($track);
    }

    public function tracksAction(Request $request, $track) {
            $crawler = new LastFmCrawler();
            $tracks = $crawler->searchTrack($track);
            if(!$tracks)
                $tracks = array('error' => array('code' => 1, 'description' => 'No songs found'));
            return JsonResponse::create($tracks);
    }
    
    private function renderPlayer($track, $artist = '') {
            $crawler = new LastFmCrawler();
            $tracks = $crawler->searchTrack($track, $artist);
            if(!$tracks)
                $json = array('error' => array('code' => 1, 'description' => 'No songs found'));
            else {
                $LastFmUrl = $crawler->getBestTrack($tracks);
                $youtubeURI = $crawler->getYoutubeUri($LastFmUrl->{'url'});
                if(!$youtubeURI) {
                    $youtube = new YoutubeCrawler();
                    $altTracks = $youtube->searchTracks($track, $artist);
                    if($altTracks)
                       $json = array('youtubeURI' => $altTracks[0]);
                    else
                       $json = array('error' => array('code' => 2, 'description' => 'No stream found'));
                } else {
                    $json = array('youtubeURI' => $youtubeURI);
                }
            }
            return JsonResponse::create($json);
    }
    
    public function eventsAction(Request $request) {
        $crawler = new LastFmCrawler();
        $args = array();
        if($request->get('location') != null) $args['location'] = $request->get('location');
        if($request->get('long') != null) $args['long'] = $request->get('long');
        if($request->get('lang') != null) $args['lang'] = $request->get('lang');
        if($request->get('radius') != null) $args['radius'] = $request->get('radius');
        if($request->get('limit') != null) $args['limit'] = $request->get('limit');
        if($request->get('page') != null) $args['page'] = $request->get('page');
        $json = json_encode($crawler->searchEvents($args));
        
        if(!$json)
            $json = array('error' => array('code' => 3, 'description' => 'No events found'));
            
        return JsonResponse::create($json);
    }
}

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