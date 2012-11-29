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
	
	public function loadMetrosInDatabase() {
		$json = $this->getMetros();
		$em = $this->getDoctrine()->getEntityManager();
		foreach($json->metros->metro as $rawMetro) {
			$metro = new Metro();
			$metro->setCountry($rawMetro->country);
			$metro->setName($rawMetro->name);
			$em->persist($metro);
		}
		$em->flush();
	}
	
	public function getMetros() {
		$url = 'http://ws.audioscrobbler.com/2.0/?method=geo.getmetros&country=&api_key=dcd351ddc924b09be225a82db043311c&format=json';
		
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
