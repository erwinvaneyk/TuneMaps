<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_venues")
 */
class Venue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
	/**
	 * @OneToOne(targetEntity="Location")
	 */
	protected $location;

}