<?php

namespace TuneMaps\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TuneMaps\RecommendationBundle\Entity;
use TuneMaps\ServiceBundle\Models;
use TuneMaps\ServiceBundle\Entity as Source;


class ServiceController extends Controller
{
    public function artistAction(Request $request, $track, $artist)
    {
            return $this->renderPlayer($request, $track,$artist);
    }
	
    public function trackAction(Request $request, $track)
    {
            return $this->renderPlayer($request, $track);
    }
    
    //register a song played
    public function songPlayedAction(Request $request,$userid,$songid) {
            $json = array('status' => 'ok');    
            try {
                $em = $this->getDoctrine()->getEntityManager();
                if(($sp = $em->getRepository('TuneMaps\RecommendationBundle\Entity\SongPlayed')->findOneBy(array('song' => $songid, 'user' => $userid))) == null)
                    $sp = new Entity\SongPlayed($em->getReference('TuneMaps\UserBundle\Entity\User', $userid),$em->getReference('TuneMaps\RecommendationBundle\Entity\Song', $songid));
                $sp->incTimesPlayed();
                $em->merge($sp);
                $em->flush();
            } catch(\Exception $e) {
                $json =  array('error' => array('code' => 5, 'description' => 'Song or user does not exist'));
            }
            return JsonResponse::create($json);
    }

    //returns json-object met alle relevante tracks
    public function tracksAction(Request $request, $track) {
            $crawler = new Models\LastFmCrawler;
            $tracks = $crawler->searchTrack($track);
            if(!$tracks)
                $tracks = array('error' => array('code' => 1, 'description' => 'No songs found'));
            return JsonResponse::create($tracks);
    }
    
    //returns json-object met een youtube-URI
    private function renderPlayer(Request $request, $track, $artist_name = '') {
            //check database
            $em = $this->getDoctrine()->getEntityManager();
            $searchSong = array('title' => $track);
            if($artist_name != '') {
                if(($artist = $em->getRepository('TuneMaps\RecommendationBundle\Entity\Artist')->findOneBy(array('name' => $artist_name))) != null) {
                    $searchSong['artist_id'] = $artist->getId();
                }
            }
            if(($song = $em->getRepository('TuneMaps\RecommendationBundle\Entity\Song')->findOneBy(array('title' => $track, 'artist' => $artist))) != null) {
                if(($source = $em->getRepository('TuneMaps\ServiceBundle\Entity\MusicSource')->findOneBy(array('song' => $song))) != null) {
                    $json = array('youtubeURI' => $source->getUri(), 'cache' => true);
                }
            }

            if(empty($json)) {
                //crawl web for uri
                $crawler = new Models\LastFmCrawler();
                $tracks = $crawler->searchTrack($track, $artist_name);
                if(!$tracks)
                    $json = array('error' => array('code' => 1, 'description' => 'No songs found'));
                else {
                    $bestTrack = $crawler->getBestTrack($tracks);
                    $youtubeURI = $crawler->getYoutubeUri($bestTrack->{'url'});
                    if(!$youtubeURI) {
                        $youtube = new Models\YoutubeCrawler();
                        $altTracks = $youtube->searchTracks($track, $artist_name);
                        if($altTracks) {
                            $json = array('youtubeURI' => $altTracks[0]);
                        } else
                        $json = array('error' => array('code' => 2, 'description' => 'No stream found'));
                    } else {
                        //build entities
                        $source = new Source\MusicSource();
                        $source->setUri($youtubeURI);
                        $source->setRank(100);
                        if(empty($song)) {
                            $song = new Entity\Song();
                            $song->setTitle($track);
                            $song->setId($bestTrack->{'mbid'});
                            if(empty($artist) && $artist_name != '') {
                                $artist = new Entity\Artist();
                                //get artist
                                $info = $crawler->trackInfo(array('mbid' => $song->getId()));
                                $artist = $info->getArtist();
                                $em->persist($artist);
                            }
                            $song->setArtist($artist);
                            $em->persist($song);
                            $source->setSong($song);
                        }
                        $source->setType('youtube');
                        if($em->getRepository('TuneMaps\ServiceBundle\Entity\MusicSource')->findBy(array('uri' => $source->getUri())) == null) {
                            $em->persist($source);
                            $em->flush();
                        }
                        $json = array('youtubeURI' => $source->getUri(), 'cache' => false);
                    }
                }
            }
            return JsonResponse::create($json);
    }
    
    public function eventsAction(Request $request) {
        $crawler = new Models\LastFmCrawler();
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
    
    public function trackinfoAction(Request $request) {
        $crawler = new Models\LastFmCrawler();
        if($request->get('mbid') != null) $args['mbid'] = $request->get('mbid');
        if($request->get('track') != null) $args['track'] = $request->get('track');
        if($request->get('artist') != null) $args['artist'] = $request->get('artist');
        $json = $crawler->trackInfo($args);
        
        if(!$json)
            $json = array('error' => array('code' => 1, 'description' => 'No song found'));
        
        return JsonResponse::create($json);
    }
}



