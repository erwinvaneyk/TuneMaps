<?php

namespace TuneMaps\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use TuneMaps\MusicDataBundle\Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\OneToOne(targetEntity="\TuneMaps\MusicDataBundle\Entity\Location", cascade={"all"}) 
     * 
    */
    protected $lastLocation;
    
    /**
     * Creates a user
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Gets the id
     * 
     * @return int The id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Sets the id
     * 
     * @param int $id The id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Gets the last location
     * 
     * @return Location The last location
     */
    public function getLastLocation() {
        return $this->lastLocation;
    }
    
    /**
     * Sets the last location
     * 
     * @param Location $lastLocation The last location
     */
    public function setLastLocation($lastLocation) {
        $this->lastLocation = $lastLocation;
    }
    
}