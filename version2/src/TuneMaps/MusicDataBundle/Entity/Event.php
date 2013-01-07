<?php

namespace TuneMaps\MusicDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * An event
 * 
 * @ORM\Entity
 * @ORM\Table(name="event")
 *
 * @author Rolf Jagerman <rolf.jagerman@contended.nl>
 */
class Event {
    
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
     * The venue
     * 
	 * @ORM\ManyToOne(targetEntity="Venue") 
     * 
     * @var Venue
	 */
	protected $venue;
	
	/**
     * The name
     * 
	 * @ORM\Column(type="string", length=255)
     * 
     * @var string
	 */
	protected $name;
	
	/**
     * The datetime
     * 
	 * @ORM\Column(type="datetime")
     * 
     * @var string
	 */
	protected $datetime;
	
	/**
     * The attending artists
     * 
     * @ORM\ManyToMany(targetEntity="Artist")
     * @ORM\JoinTable(name="event_artist",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="artist_id", referencedColumnName="id")}
     * )
     * 
     * @var array
     */
	protected $attendingArtists;
    
    /**
     * Creates a new event
     */
    public function __construct() {
        $this->attendingArtist = new ArrayCollection();
    }
    
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
     * Gets the venue
     * 
     * @return Venue venue
     */
    public function getVenue() {
        return $this->venue;
    }
    
    /**
     * Sets the venue
     * 
     * @param Venue $venue
     */
    public function setVenue($venue) {
        $this->venue = $venue;
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
     * Gets the datetime
     * 
     * @return string datetime
     */
    public function getDatetime() {
        return $this->datetime;
    }
    
    /**
     * Sets the datetime
     * 
     * @param string $datetime
     */
    public function setDatetime($datetime) {
        $this->datetime = $datetime;
    }
    
    /**
     * Gets the attendingArtists
     * 
     * @return array attendingArtists
     */
    public function getAttendingArtists() {
        return $this->attendingArtists;
    }
    
    /**
     * Sets the attendingArtists
     * 
     * @param array $attendingArtists
     */
    public function setAttendingArtists($attendingArtists) {
        $this->attendingArtists = $attendingArtists;
    }
    
}
