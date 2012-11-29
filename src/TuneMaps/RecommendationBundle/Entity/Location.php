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

}