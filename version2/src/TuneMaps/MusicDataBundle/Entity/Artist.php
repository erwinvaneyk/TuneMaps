<?php
namespace TuneMaps\MusicDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * An artist
 * 
 * @ORM\Entity
 * @ORM\Table(name="artist")
 */
class Artist {
    
    /**
     * The identifier
     * 
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     * 
     * @var string
     */
    protected $id;
    
    /**
     * The artist's name
     * 
     * @ORM\Column(type="string", length=255)
     * 
     * @var string
     */
    protected $name;
    
    /**
     * The songs this artist has made
     * 
	 * @ORM\OneToMany(targetEntity="Song", mappedBy="artist")
     * 
     * @var array
	 */
	protected $songs;
	
	/**
     * The events this user attends
     * 
	 * @ORM\ManyToMany(targetEntity="Event", mappedBy="attendingArtists")
     * 
     * @var array
	 */
	protected $events;
        
    /**
     * Gets the id
     * 
     * @return string id
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * Sets the id
     * 
     * @param string $id
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
     * Gets the songs
     * 
     * @return array songs
     */
    public function getSongs() {
        return $this->songs;
    }
    
    /**
     * Sets the songs
     * 
     * @param array $songs
     */
    public function setSongs($songs) {
        $this->songs = $songs;
    }
    
    /**
     * Gets the events
     * 
     * @return array events
     */
    public function getEvents() {
        return $this->events;
    }
    
    /**
     * Sets the events
     * 
     * @param array $events
     */
    public function setEvents($events) {
        $this->events = $events;
    }
    
}
