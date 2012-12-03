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
		$json = "http://ws.audioscrobbler.com/2.0/?method=geo.getEvents&api_key=dcd351ddc924b09be225a82db043311c&format=json&limit=100&distance=300";
		$events = array();
		foreach($json->{'events'}->{'event'} as $key=>$event) {
			$events[$key] = new Entity\Event;
			$events[$key]->setName($event->{'id'});
			$events[$key]->setDateTime(date_parse($event->{'startDate'}));
			$venue = new Entity\Venue($event->{'venue'}->{'id'});
			$location = new Entity\Location;
			$location->setLattitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:lat'});
			$location->setLongitude($event->{'venue'}->{'location'}->{'geo:point'}->{'geo:long'});
			$venue->setLocation($location);
			$events[$key]->setVenue($venue);
		}
		return $this->render('TuneMapsFrontBundle:Main:events.html.twig', array('events' => $events));
	}
	
	public function trendsAction()
	{
		return $this->render('TuneMapsFrontBundle:Main:trends.html.twig', array());
	}
	
}
?>