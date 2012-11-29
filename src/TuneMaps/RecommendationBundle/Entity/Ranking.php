<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_chart")
 */
class Ranking
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Song")
	 */
	protected $song;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Metro")
	 */
	protected $metro;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $week;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $rank;
	
	public function setSong($song) {
		$this->song = $song;
	}
	
	public function getSong() {
		return $this->song;
	}
	
	public function setMetro($metro) {
		$this->metro = $metro;
	}
	
	public function getMetro() {
		return $this->metro;
	}
	
	public function setWeek($week) {
		$this->week = $week;
	}
	
	public function getWeek() {
		return $this->week;
	}
	
	public function getRank() {
		return $this->rank;
	}
	
	public function setRank($rank) {
		$this->rank = $rank;
	}

}