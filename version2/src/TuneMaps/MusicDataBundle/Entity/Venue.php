<?php

namespace TuneMaps\MusicDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A venue
 * 
 * @ORM\Entity
 * @ORM\Table(name="venue")
 */
class Venue
{
    /**
     * The identifier
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * 
     * @var int
     */
    protected $id;
	
	/**
     * The name
     * 
	 * @ORM\Column(type="string", length=255)
     * 
     * @var string
	 */
    protected $name;
	
	/**
     * The location
     * 
	 * @ORM\OneToOne(targetEntity="Location", cascade={"all"}) 
     * 
     * @var Venue
	 */
	protected $location;
    
    /**
     * Gets the id
     * 
     * @return int id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Sets the id
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Gets the name
     * 
     * @return string name
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Sets the name
     * 
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Gets the location
     * 
     * @return Location location
     */
    public function getLocation() {
        return $this->location;
    }
    
    /**
     * Sets the location
     * 
     * @param Location $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }
     
}
