<?php

namespace TuneMaps\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\YoutubeCrawler;
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
        $json = null;
        //try to find code internally
        $em = $this->getDoctrine()->getEntityManager();
        if(($artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artist_name))) != null) {
            if(($song = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Song')->findOneBy(array('title' => $tracktitle, 'artist' => $artist))) != null) {
                $json = array('artist' => $artist_name, 'title' => $tracktitle, 'youtube' => $song->{'youtube'});
            }
        }
        
        //retrieve code externally
        if($json == null) {
            //retrieve  code from youtube
            $youtubeCrawler = new YoutubeCrawler();
            $youtube = $youtubeCrawler->getFirstVideo($artist_name . ' ' . $tracktitle);
            $json = array('artist' => $artist_name, 'title' => $tracktitle, 'youtube' => $youtube);
            //save song/artist
        }
        
        //return result
        return new JsonResponse($json);
    }
    
}
