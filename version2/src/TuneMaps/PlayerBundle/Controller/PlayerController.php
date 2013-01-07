<?php

namespace TuneMaps\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\YoutubeCrawler;

class PlayerController extends Controller
{
    
    /**
     * Searches for a track and gives the youtube uri
     * 
     * @Route("/player/{artist}/{title}", name="youtubecode")
     */
    function youtubeCodeAction($artist, $title) {
        $youtubeCrawler = new YoutubeCrawler();
        $youtube = $youtubeCrawler->getFirstVideo($artist . ' ' . $title);
        $json = array('artist' => $artist, 'title' => $title, 'youtube' => $youtube);
        return new JsonResponse($json);
    }
    
}
