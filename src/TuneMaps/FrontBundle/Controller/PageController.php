<?php

namespace TuneMaps\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TuneMaps\ServiceBundle\models;
class PageController extends Controller
{
    public function homeAction()
    {
        return $this->render('TuneMapsFrontBundle::home.html.twig', array());
    }
	
	public function mainAction()
	{
		return $this->render('TuneMapsFrontBundle:Main:main.html.twig', array());
	}
	
	public function mapAction()
	{
		return $this->render('TuneMapsFrontBundle:Main:map.html.twig', array());
	}
	
	public function timelineAction()
	{
		return $this->render('TuneMapsFrontBundle:Main:timeline.html.twig', array());
	}

	public function eventsAction()
	{
		$lastfm = new \TuneMaps\ServiceBundle\models\LastFmCrawler();
		$events = $lastfm->searchEvents(array('limit' => 30, 'festivalsonly' => 1));
		return $this->render('TuneMapsFrontBundle:Main:events.html.twig', array('events' => $events));
	}
	
	public function trendsAction()
	{
		return $this->render('TuneMapsFrontBundle:Main:trends.html.twig', array());
	}
	
}
?>