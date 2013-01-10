<?php
/*
 * @author D. Eikelenboom
 */
namespace TuneMaps\CrawlerBundle\Entity;

use TuneMaps\MusicDataBundle\Entity\Artist;
use TuneMaps\MusicDataBundle\Entity\Event;
use TuneMaps\MusicDataBundle\Entity\Location;
use TuneMaps\MusicDataBundle\Entity\Song;
use TuneMaps\MusicDataBundle\Entity\Venue;

/**
 * A crawler for Last FM
 */
class EventRecommender {
    
	/*
	 * @param n number of events
	 * @param location of user
	 * @return list of n best matching events given location
	 */
	public function getEvents($n, $location){
		
		$crawler = new LastFMCrawler();
		$events = $crawler->getEvents($location);
		$user = $this->get('security.context')->getToken()->getUser();
		
		foreach($events as $event){
			//get all attending artists
			$artists = $event->getAttendingArtists();
			
			$playcount = 0;
			foreach($artists as $artist_partial){
				
				//get playcount for an artist for this user
				//$artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artistname));
				//if ($artist == NULL){
					//retrieve from crawler if not in own db
					$artist = $crawler->artistInfo(array('name' => $artist_partial->getName()));
				//}				
				//echo $artist;
				/*$artistPlayed = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed')->findOneBy(array('artist' => $artist->getId(), 'user' => $user->getId()));

				//get playcount
				if($artistPlayed != null) {
					$playcount = $artistPlayed->getTimesPlayed();
				}*/
			}
		}
		
		//sort on highest playcount
		return 'test';
	}
	
	/*
	 * get playcount for each song of an artist
	 */
	protected function getPlayCount($artist){
		return null;
	}
}
?>