<?php

namespace TuneMaps\PlayerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use TuneMaps\CrawlerBundle\Entity\YoutubeCrawler;
use TuneMaps\CrawlerBundle\Entity\LastFMCrawler;
use TuneMaps\MusicDataBundle\Entity\Song;
use TuneMaps\MusicDataBundle\Entity\Artist;
use TuneMaps\MusicDataBundle\Entity\ArtistPlayed;

class PlayerController extends Controller
{
    
    /**
     * Searches for a track and gives the youtube uri
     * 
     * @Route("/player/{artist_name}/{tracktitle}", name="youtubecode")
     */
    function youtubeCodeAction($artist_name, $tracktitle) {
	
		$em = $this->getDoctrine()->getEntityManager();
		
		// Initialize empty value
		$json = array('artist' => $artist_name, 'title' => $tracktitle, 'youtube' => '');
	
		// Find song
		$song = $this->getSongFromDatabase($artist_name, $tracktitle);
		if($song == null) {
			$song = $this->getSongFromLastFM($artist_name, $tracktitle);
			$em->persist($song->getArtist());
			$em->persist($song);
			$em->flush();
		}
		
		// Song found, get the youtube URI and store the song object
		if($song != null) {
		
			// Get the youtube URI if it does not exist
			if(!$song->getYoutube()) {
				$youtubeCrawler = new YoutubeCrawler();
				$youtube = $youtubeCrawler->getFirstVideo($song->getArtist()->getName() . ' ' . $song->getTitle());
				$song->setYoutube($youtube);
			}
			
			// Fill the json array with correct information
			$json['artist'] = $song->getArtist()->getName();
			$json['title'] = $song->getTitle();
			$json['youtube'] = $song->getYoutube();
			
			// Store the objects in the database
			$em->merge($song->getArtist());
			$em->merge($song);
			$em->flush();
			
			// If there is a youtube link, increment the playcount
			if(strlen($song->getYoutube()) > 0) {
				$this->incrementPlayCount($song->getArtist(), $em);
			}
			
		}
		
		// Return the response
		return new JsonResponse($json);
		
	}
	
	/**
	 * Increments the play count for given artist
	 * 
	 * @param Artist $artist The artist
	 */
	public function incrementPlayCount($artist, $em) {
	
		// Get the logged in user
		$user = $this->get('security.context')->getToken()->getUser();
		
		// Get the current play count for given artist
		$playcount = $em->getRepository('TuneMaps\MusicDataBundle\Entity\ArtistPlayed')->findOneBy(array('user' => $user, 'artist' => $artist));
		
		// Check if a playcount exists
		if($playcount == null) {
			
			// If there is no play count for this artist yet, create it
			$playcount = new ArtistPlayed($user, $artist);
			$em->persist($playcount);
			
		} else {
		
			// Increment the play count if at least a minute has passed since the last listening
			if((time() - $playcount->getLastPlayed()->getTimestamp()) > 60) {
				$playcount->incTimesPlayed();
			}
			$em->merge($playcount);
			
		}
		
		// Store the playcount
		$em->flush();
		
	}
	
	/**
	 * Attempts to get the song from the database
	 * 
	 * @param string $artistname The artist's name
	 * @param string $title The title
	 * @return Song The song
	 */
	protected function getSongFromDatabase($artistname, $title) {
		$em = $this->getDoctrine()->getEntityManager();
		$youtubeUri = '';
		$artist = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Artist')->findOneBy(array('name' => $artistname));
		$song = null;
		if($artist != null) {
			$song = $em->getRepository('TuneMaps\MusicDataBundle\Entity\Song')->findOneBy(array('title' => $title, 'artist' => $artist));
		}
		return $song;
	}
	
	/**
	 * Gets the song from the last fm API
	 * 
	 * @param string $artist The artist
	 * @param string $title The title
	 * @return Song The song
	 */
	protected function getSongFromLastFM($artist, $title) {
		$lastFmCrawler = new LastFMCrawler();
		$song = $lastFmCrawler->trackInformation($artist, $title);
		return $song;
	}
    
}
