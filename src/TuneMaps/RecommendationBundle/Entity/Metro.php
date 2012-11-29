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
    protected $name;
	
	/**
     * @ORM\Column(type="string", length=255)
     */
    protected $country;
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setCountry($country) {
		$this->country = $country;
	}
	
	public function getCountry() {
		return $this->country;
	}

}