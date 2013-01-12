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
		$artistrepository = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist');
		$artistplayedrepository = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed');
		
		/*
		 * Event Recommendation Algorithm
		 * @author D. Eikelenboom
		 * @return list of events
		 */
		$user = $this->get('security.context')->getToken()->getUser();
				
		foreach($events as $event){
			//get all attending artists
			$artists = $event->getAttendingArtists();
			
			$sumPlaycount = 0;
			foreach($artists as $artist_partial){
				//get playcount for an artist for this user
				$artist = $artistrepository->findOneBy(array('name' => $artist_partial->getName()));
				
				$playcount;
				if ($artist == NULL){
					//if artist is not in database for this user, then it has not been played yet
					$playcount = 0;
				}
				else{
					//retrieve from crawler if not in own db
					$artistPlayed = $artistplayedrepository->findOneBy(array('artist' => $artist->getId(), 'user' => $user->getId()));
					//get playcount
					if($artistPlayed != null)
						$playcount = $artistPlayed->getTimesPlayed();
				}
				//and sum the playcounts for the event
				$sumPlaycount = $sumPlaycount + $playcount;
			}
			
			//total sum of playcounts for this event
			$event->rank = $sumPlaycount;
		}
		
		//sort events on rank/playcount
		usort($events, function($a, $b){  return $a->rank < $b->rank; });
		
		//limit to a list of 10 events
		$events = array_slice($events, 0, 10);
		
        return array('events' => $events);
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
