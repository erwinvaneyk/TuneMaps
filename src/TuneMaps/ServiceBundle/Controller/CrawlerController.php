<?php

namespace TuneMaps\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use TuneMaps\UserBundle\Entitiy;
use TuneMaps\ServiceBundle\Models;

class CrawlerController extends Controller {
    
    //werkt goed
    public function usersAction(Request $request,$page) {
        $json = array('status' => 'ok');
        $cr = new Models\LastFmCrawler();
        //get users
        if(!($users = $cr->getActiveUsers($page))) {
            $json =  array('error' => array('code' => 7, 'description' => 'No users found!'));
        } else {

            //get userinfo
            $count = 0;
            $usernames = array();
            $em = $this->getDoctrine()->getEntityManager();
            foreach($users as $key=>$username) {
                if(($sp = $em->getRepository('TuneMaps\UserBundle\Entity\User')->findOneBy(array('username' => $username))) == null) {
                    $users[$key] = $cr->getUserInfo($username);
                    $em->merge($users[$key]);
                    $count++;
                    $usernames[] = $username;
                }
            }
            $json['inserts'] = $count;
            $json['users'] = $usernames;
            //insert into database
            @$em->flush();
        }
        return JsonResponse::create($json);
    }
    
    //errors
    public function recentTracksAction(Request $request,$username,$page) {
        $cr = new Models\LastFmCrawler();
        //get data (array of SongPlayed-objects)
        if(!($recentTracks = $cr->getRecentTracks($username, $page))) {
            $json =  array('error' => array('code' => 8, 'description' => 'No recent tracks found for user: ' . $username . '!'));
        } else {
            //insert into database
            $count = 0;
            $em = $this->getDoctrine()->getEntityManager();
            foreach($recentTracks as $track) {
                //haal een referentie op als de song al in de database zit
                
                if(($song = $em->getRepository('TuneMaps\RecommendationBundle\Entity\Song')->findOneBy(array("id" => $track->getSong()))) != null) {
                    $track->setSong($song);
                } else {
                    //zo niet creer nieuw song object, en gebruik 
                    $song = $cr->trackInfo(array("mbid" => $track->getSong()));
                    if(!$song) continue;
                    $em->merge($song->getArtist());
                    $em->merge($song);
                    $track->setSong($song);
                }
                $track->setUser($em->getReference('TuneMaps\UserBundle\Entity\User',$this->getUser()->getId()));
                $em->merge($track);
                $count++;
                $em->flush();
            }
            //$em->flush();
            $json = array('status' => 'ok', 'inserts' => $count);
        }
        return JsonResponse::create($json);
    }
    
    public function topTracksAction(Request $request,$username,$page) {
        $cr = new Models\LastFmCrawler();
        //get data
        if(!($topTracks = $cr->getTopTracks($username, $page))) {
            $json =  array('error' => array('code' => 8, 'description' => 'No recent tracks found for user: ' . $username . '!'));
        }
        
        //insert into database
        $em = $this->getDoctrine()->getEntityManager();
        $em->merge($topTracks);
        $em->flush();
        
        return JsonResponse::create($topTracks);
    }
    
    public function displayAction(Request $request) {
        $cr = new Models\LastFmCrawler();
        //var_dump($cr->getRecentTracks("jb"));
        
        return $this->render('TuneMapsServiceBundle:Crawler:display.html.twig', array());
    }
}

?>
