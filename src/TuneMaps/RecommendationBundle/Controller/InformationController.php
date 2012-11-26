<?php

namespace TuneMaps\RecommendationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InformationController extends Controller
{
    public function artistAction(Request $request, $track, $artist)
    {
            return $this->renderPlayer($track,$artist);
    }
	
    
}
