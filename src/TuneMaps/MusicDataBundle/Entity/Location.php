<?php

namespace TuneMaps\MusicDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A location
 * 
 * @ORM\Entity
 * @ORM\Table(name="location")
 */
class Location {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * The latitude
     * 
	 * @ORM\Column(type="decimal", scale=4)
     * 
     * @var double
	 */
	protected $latitude;
	
	/**
     * The longitude
     * 
	 * @ORM\Column(type="decimal", scale=4)
     * 
     * @var double
	 */
	protected $longitude;
        
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
     * Gets the latitude
     * 
     * @return double latitude
     */
    public function getLatitude() {
        return $this->latitude;
    }
    
    /**
     * Sets the latitude
     * 
     * @param double $latitude
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }
    
    /**
     * Gets the longitude
     * 
     * @return double longitude
     */
    public function getLongitude() {
        return $this->longitude;
    }
    
    /**
     * Sets the longitude
     * 
     * @param double $longitude
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }
    
}
