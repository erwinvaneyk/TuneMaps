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
        
        return array();
    }
}
