<?php

namespace TuneMaps\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\LastFMCrawler;
use TuneMaps\CrawlerBundle\Entity\EventRecommender;
use TuneMaps\MusicDataBundle\Entity\Event;

class PageController extends Controller
{
    /**
     * @Route("/", name="showpage")
     * @Template("TuneMapsFrontBundle::main.html.twig")
     */
    public function showpageAction()
    {
        return array();
    }
    
    /**
     * @Route("/events", name="events")
     * @Template("TuneMapsFrontBundle:Contents:events.html.twig")
     */
    public function eventsAction()
    {
		$em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        
        $lastFmCrawler = new LastFMCrawler();
        $events = $lastFmCrawler->getEvents($user->getLastLocation());
		
		//Event Recommender
		$user = $this->get('security.context')->getToken()->getUser();
		
		foreach($events as $event){
			//get all attending artists
			$artists = $event->getAttendingArtists();
			$playcount = 1000;
			
			foreach($artists as $artist_partial){
				//get playcount for an artist for this user
				$artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artist_partial->getName()));
				var_dump($artist);
				/*$artistPlayed = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed')->findOneBy(array('artist' => $artist->getId(), 'user' => $user->getId()));
				echo $artistPlayed.'<br/>';
				
				//get playcount
				if($artistPlayed != null) {
					$playcount = $artistPlayed->getTimesPlayed();
				}*/
			}
		}
        
        return array('events' => $events, 'recommendedEvents' => $playcount);
    }
    
    /**
     * @Route("/charts", name="charts")
     * @Template("TuneMapsFrontBundle:Contents:charts.html.twig")
     */
    public function chartsAction()
    {
        
		$lastFmCrawler = new LastFmCrawler();
		$chart = $lastFmCrawler->getChart('Netherlands', 'Amsterdam', 1356868800);
		$predictedIndices = array(12, 9, 6, 11, 7, 3, 10, 13, 5, 8);
		
		$thisweek = array();
		$nextweek = array();
		for($i = 0; $i<10; $i++) {
			$thisweek[] = $chart[$i];
			$nextweek[] = $chart[$predictedIndices[$i]];
		}
		
        return array('thisweek' => $thisweek, 'nextweek' => $nextweek);
    }
}
