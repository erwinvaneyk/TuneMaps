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
			$event->setRank($sumPlaycount);
		}
		
		//sort events on rank/playcount
		usort($events, function($a, $b){  return $a->getRank() < $b->getRank(); });
		
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
        
		// Find the metro
		$country = 'United States';
		$metro = 'Austin';
		
		// Get the most recent chart
		$lastFmCrawler = new LastFmCrawler();
		$chart = $lastFmCrawler->getLatestChart($country, $metro);
		
		
		// Default prediction file if no prediction could be found
		$predictedIndices = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
		
		// Open predicted indices
		$file = __DIR__ . '/../../../../chartprediction/data/' . $metro . '.predict.csv';
		if(file_exists($file)) {
			$fileHandle = fopen($file, 'r');
			if($fileHandle !== false) {
				$prediction = fgetcsv($fileHandle);
				if(is_array($prediction) && count($prediction) == 10) {
					$predictedIndices = $prediction;
				}
			}
		}
		
		// Build arrays of songs based on predicted indices
		$thisweek = array();
		$nextweek = array();
		for($i = 0; $i<10; $i++) {
			$thisweek[] = $chart[$i];
			$nextweek[] = $chart[$predictedIndices[$i]-1];
		}
		
		// Return the arrays to the template
        return array('thisweek' => $thisweek, 'nextweek' => $nextweek);
    }
}
