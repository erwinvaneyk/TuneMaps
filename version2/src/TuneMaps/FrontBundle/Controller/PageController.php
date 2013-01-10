<?php

namespace TuneMaps\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\LastFMCrawler;
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
        $user= $this->get('security.context')->getToken()->getUser();
        
        $lastFmCrawler = new LastFMCrawler();
        $events = $lastFmCrawler->getEvents($user->getLastLocation());
        
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
