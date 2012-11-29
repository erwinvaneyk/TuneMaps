<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use TuneMaps\RecommendationBundle\Entity\Location as Location;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_venue")
 */
class Venue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Location")
	 */
	protected $location;

}