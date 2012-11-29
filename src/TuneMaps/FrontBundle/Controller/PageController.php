<?php

namespace TuneMaps\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
		$events = array();
		return $this->render('TuneMapsFrontBundle:Main:events.html.twig', array($events));
	}
	
}
?>