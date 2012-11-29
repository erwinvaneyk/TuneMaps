<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_location")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $lattitude;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $longitude;
	
	public function getLattitude() {
		return $this->lattitude;
	}
	
	public function setLattitude($lattitude) {
		$this->lattitude = $lattitude;
	}
	
	public function getLongitude() {
		return $this->longitude;
	}
	
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}

}