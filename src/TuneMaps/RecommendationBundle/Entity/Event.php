<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_event")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Venue") 
	 */
	protected $venue;
	
	public function getVenue() {
		return $this->venue;
	}
	
	public function setVenue($venue) {
		$this->venue = $venue;
	}

}