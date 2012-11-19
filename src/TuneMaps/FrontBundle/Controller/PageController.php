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
}
