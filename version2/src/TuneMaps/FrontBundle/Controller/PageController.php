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
			
			$sumPlaycount = 0;
			foreach($artists as $artist_partial){
				//get playcount for an artist for this user
				$artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artist_partial->getName()));
				if ($artist == NULL){
					//retrieve from crawler if not in own db
					$artist = $lastFmCrawler->artistInfo(array('artist' => $artist_partial->getName()));
				}
				$artistPlayed = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed')->findOneBy(array('artist' => $artist->getId(), 'user' => $user->getId()));
								       
				//get playcount
				$playcount = 1;
				if($artistPlayed != null)
					$playcount = $artistPlayed->getTimesPlayed();
				
				$sumPlaycount = $sumPlaycount + $playcount;
			}
			
			//total sum of playcounts for this event
			$event->rank = $sumPlaycount;
		}
		
		//sort events on rank/playcount
		usort($events, function($a, $b){  return $a->rank < $b->rank; });
		
        return array('events' => $events);
    }
    
    /**
     * @Route("/charts", name="charts")
     * @Template("TuneMapsFrontBundle:Contents:charts.html.twig")
     */
    public function chartsAction()
    {
        
        return array();
    }
}
