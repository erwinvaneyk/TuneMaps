<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_events")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
	/**
	 *  
	 */
	protected $venue;

}