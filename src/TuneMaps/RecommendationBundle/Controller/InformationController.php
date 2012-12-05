<?php

namespace TuneMaps\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TuneMaps\RecommendationBundle\Entity\Metro as Metro;

class InformationController extends Controller
{
    public function metrosToDatabaseAction(Request $request)
    {
        $this->loadMetrosInDatabase();
		$response = new Response('Done');
		return $response;
    }
	
	public function rankingsAction(Request $request) {
		return $this->render('TuneMapsRecommendationBundle::crawler.html.twig', array());
	}
	
	public function rankingsToDatabaseAction(Request $request, $geo, $start, $end)
	{
		
		$this->loadRankingsInDatabase($geo, $start, $end);
		
		$response = new Response('done');
		return $response;
	}
	
	protected function loadRankingsInDatabase($geoName, $start, $end) {
		
		$em = $this->getDoctrine()->getEntityManager();
		$metro = $em->getRepository('\TuneMaps\RecommendationBundle\Entity\Metro')->find($geoName);
		
		$json = $this->getJSON('http://ws.audioscrobbler.com/2.0/?method=geo.getmetrotrackchart&country=' . $metro->getCountry() . '&metro=' . $metro->getName() . '&api_key=dcd351ddc924b09be225a82db043311c&format=json&start=' . $start . '&end=' . $end);
		if($json->toptracks == null) { return; }
		foreach($json->toptracks->track as $rawtrack) {
			$mbid = $rawtrack->mbid;
			$song = $em->getRepository('\TuneMaps\RecommendationBundle\Entity\Song')->find($mbid);
			$artist = $em->getRepository('\TuneMaps\RecommendationBundle\Entity\Artist')->find($rawtrack->artist->mbid);
			
			if($song === null) {
				$song = new \TuneMaps\RecommendationBundle\Entity\Song();
				$song->setTitle($rawtrack->name);
				$song->setId($mbid);
				if($artist === null) {
					$artist = new \TuneMaps\RecommendationBundle\Entity\Artist();
					$artist->setId($rawtrack->artist->mbid);
					$artist->setName($rawtrack->artist->name);
					if(strlen($artist->getId()) > 0) {
						$em->persist($artist);
					}
				}
				if(strlen($song->getId()) > 0 && strlen($artist->getId()) > 0) {
					$song->setArtist($artist);
					$em->persist($song);
				}
			}
			
			
			if(strlen($song->getId()) > 0 && strlen($artist->getId()) > 0) {
				$rank = $rawtrack->{'@attr'}->rank;
				$ranking = new \TuneMaps\RecommendationBundle\Entity\Ranking();
				$ranking->setSong($song);
				$ranking->setMetro($metro);
				$ranking->setRank($rank);
				$ranking->setWeek($start);
				$em->persist($ranking);
			}
			
		}
		$em->flush();
		//print_r($metro);
	}
	
	protected function loadMetrosInDatabase() {
		$json = $this->getJSON('http://ws.audioscrobbler.com/2.0/?method=geo.getmetros&country=&api_key=dcd351ddc924b09be225a82db043311c&format=json');
		$em = $this->getDoctrine()->getEntityManager();
		foreach($json->metros->metro as $rawMetro) {
			$metro = new Metro();
			$metro->setCountry($rawMetro->country);
			$metro->setName($rawMetro->name);
			$em->persist($metro);
		}
		$em->flush();
	}
	
	protected function getJSON($url) {
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$url);
		curl_setopt ($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		$raw = curl_exec($curl_handle);
		curl_close($curl_handle);
        return json_decode($raw);
	}
    
}
