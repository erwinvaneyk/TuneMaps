<?php
namespace TuneMaps\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

use TuneMaps\MusicDataBundle\Entity\Location;

class UserController extends Controller {
    
    /**
     * Updates the location of the user
     * 
     * @Route("/location/{lat}/{lon}", name="locationupdate")
     */
    public function locationAction($lat,$lon) {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->get('security.context')->getToken()->getUser();
        $lat = floatval($lat);
        $lon = floatval($lon);
        if(!(empty($lat) || empty($lon))) {
            $loc = $user->getLastLocation();
            $loc->setLongitude($lon);
            $loc->setLatitude($lat);
            $em->merge($user);
            $em->flush();
            $json = array('status' => 'ok', 'lat' => $lat, 'lon' => $lon);
        } else {
            $json = array('error' => array('status' => 2, 'descr' => 'bad inputs!'));
        }
        
        return new JsonResponse($json);
    }
}
?>
