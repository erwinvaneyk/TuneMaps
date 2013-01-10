<?php

namespace TuneMaps\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\YoutubeCrawler;
use TuneMaps\CrawlerBundle\Entity\LastFMCrawler;
use TuneMaps\MusicDataBundle\Entity\Song;
use TuneMaps\MusicDataBundle\Entity\Artist;
use TuneMaps\MusicDataBundle\Entity\ArtistPlayed;

class PlayerController extends Controller
{
    
    /**
     * Searches for a track and gives the youtube uri
     * 
     * @Route("/player/{artist_name}/{tracktitle}", name="youtubecode")
     */
    function youtubeCodeAction($artist_name, $tracktitle) {
        //try to find code internally
        $json = null;
        $em = $this->getDoctrine()->getEntityManager();
        if(($artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artist_name))) != null) {
            if(($song = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Song')->findOneBy(array('title' => $tracktitle, 'artist' => $artist))) != null) {
                $json = array('artist' => $song->getArtist()->getName(), 'title' => $song->getTitle(), 'youtube' => $song->getYoutube());
            }
        }
        
        //retrieve code externally
        if(empty($json)) {
            //crawl web for uri
            $crawler = new LastFMCrawler();
            $tracks = $crawler->searchTrack($tracktitle . " " . $artist_name,1,1,true);
            
            //get entities of the song and artist
            $song = $crawler->trackinfo(array("mbid" => $tracks[0]->getId()));
            
            //retrieve  code from youtube
            $youtubeCrawler = new YoutubeCrawler();
            if($song->getYoutube() == null) {
                $song->setYoutube($youtubeCrawler->getFirstVideo($song->getArtist()->getName() . ' ' . $song->getTitle()));
            }
            $json = array('artist' => $song->getArtist()->getName(), 'title' => $song->getTitle(), 'youtube' => $song->getYoutube());
            
            //save entities
            $em->merge($song->getArtist());
            $em->merge($song);
            $em->flush();
        }
        
        // adjust playcount of the artist
        $this->updatePlayCount($song->getArtist()->getId());
        
        //return result
        return new JsonResponse($json);
    }
    
    // requires: artist mbID
    // adds 1 to the playcount of the current artist
    private function updatePlayCount($artist_mbid) {
        // get user and connection
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $artist = $em->getReference('TuneMaps\MusicDataBundle\Entity\Artist',$artist_mbid);
        
        // retrieve artist
        if(!($artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('id' => $artist_mbid)))) {
            return false;
        }
       
        // retrieve previous playcount or create new
        if(!($playcount = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed')->findOneBy(array('user' => $user, 'artist' => $artist)))) {
            $playcount = new ArtistPlayed($user,$artist);
        } else {
            // check if the song wasn't played 1 min ago
            if(($playcount->getLastPlayed()->getTimestamp() - time()) > 60)
                $playcount->incTimesPlayed();
        }
        
        // save new playcount
        $em->merge($playcount);
        $em->flush();
        
        return true;
    }
    
}
