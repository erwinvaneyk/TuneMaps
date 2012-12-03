<?php

namespace TuneMaps\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TuneMaps\RecommendationBundle\Entity;
use TuneMaps\ServiceBundle\Models;


class ServiceController extends Controller
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
        $json = $crawler->searchEvents($args);
        
        if(!$json)
            $json = array('error' => array('code' => 3, 'description' => 'No events found'));
            
        return JsonResponse::create($json);
    }
}



