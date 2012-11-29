<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_artist")
 */
class Artist
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    protected $id;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="Song", mappedBy="artist")
	 */
	protected $songs;
	
	public function __construct() {
		$songs = new ArrayCollection();
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getSongs() {
		return $this->songs;
	}
	
	public function setSongs($songs) {
		$this->songs = $songs;
	}

}