<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_song")
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    protected $id;
	
	/**
	 * @ORM\Column(type="string", length=255) 
	 */
	protected $title;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Artist", cascade={"persist"})
	 */
	protected $artist;
	
	/**
	 * @ORM\OneToMany(targetEntity="Ranking", mappedBy="song")
	 */
	protected $rankings;
	
        public function __construct($id = 0) {
            $this->id = $id;
        }
        
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getArtist() {
		return $this->artist;
	}
	
	public function setArtist($artist) {
		$this->artist = $artist;
	}
	
	public function getRankings() {
		return $this->rankings;
	}
	
	public function setRankings($rankings) {
		$this->rankings = $rankings;
	}

}