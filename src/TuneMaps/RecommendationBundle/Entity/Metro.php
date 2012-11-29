<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_metro")
 */
class Metro
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    protected $id;
	
	/**
     * @ORM\Column(type="string", length=255)
     */
    protected $country;
	
	/**
	 * @ORM\OneToMany(targetEntity="Ranking", mappedBy="metro")
	 */
	protected $rankings;
	
	public function setName($name) {
		$this->id = $name;
	}
	
	public function getName() {
		return $this->id;
	}
	
	public function setCountry($country) {
		$this->country = $country;
	}
	
	public function getCountry() {
		return $this->country;
	}
	
	public function setRankings($rankings) {
		$this->rankings = $rankings;
	}
	
	public function getRankings() {
		return $this->rankings;
	}

}