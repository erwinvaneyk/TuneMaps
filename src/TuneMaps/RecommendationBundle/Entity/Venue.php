<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use TuneMaps\RecommendationBundle\Entity\Location as Location;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_venue")
 */
class Venue implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
    /**
     *
     * @ORM\Column(type="string", length=255) 
     */
    protected $name;
    
    /**
        * @ORM\OneToOne(targetEntity="Location", cascade={"persist"})
        */
    protected $location;
     
    public function __construct($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getLocation() {
        return $this->location;
    }
    
    public function setLocation($location) {
        $this->location = $location;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}