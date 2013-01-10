<?php

namespace TuneMaps\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TuneMaps\CrawlerBundle\Entity\LastFMCrawler;
use TuneMaps\CrawlerBundle\Entity\YoutubeCrawler;

class SearchController extends Controller
{
    /**
     * Searches for a track and gives the response in JSON
     * 
     * @Route("/search/{query}",
     *  defaults={
     *      "page" = "1",
     *      "resultsPerPage" = "15"
     *  },
     *  name="search"
     * )
     * @Route("/search/{query}/{page}",
     *  requirements={
     *      "page" = "\d+"
     *  },
     *  defaults={
     *      "resultsPerPage" = "15"
     *  }
     * )
     * @Route("/search/{query}/{page}/{resultsPerPage}",
     *  requirements={
     *      "page" = "\d+",
     *      "resultsPerPage" = "\d+"
     *  },
     *  name="searchfull"
     * )
     * @Template("TuneMapsSearchBundle::results.html.twig")
     */
    public function searchAction($query, $page, $resultsPerPage)
    {
		$lastFmCrawler = new LastFMCrawler();
        $songs = $lastFmCrawler->searchTrack($query, $page, $resultsPerPage);
        
        return array('songs' => $songs, 'query' => $query, 'page' => $page);
    }
    
    /**
     * Searches for a track and gives the response
     * 
     * @Route("/searchwidget/{query}", name="searchwidget")
     */
    public function searchWidgetAction($query)
    {
        $lastFmCrawler = new LastFMCrawler();
        $songs = $lastFmCrawler->searchTrack($query, 1, 5);
        
        $json = array();
        foreach($songs as $track) {
            $json[] = array('artist' => $track->getArtist()->getName(), 'title' => $track->getTitle(), 'youtube' => $track->getYoutube());
        }
        
        return new JsonResponse($json);
    }
    
}
