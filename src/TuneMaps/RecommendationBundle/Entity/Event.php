<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_event")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;
	
	/**
	 * @ORM\OneToOne(targetEntity="Venue") 
	 */
	protected $venue;
	
	/**
	 * @ORM\Column(type="string", length=255)
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $datetime;
	
	/**
     * @ORM\ManyToMany(targetEntity="Artist")
     * @ORM\JoinTable(name="tunemaps_event_artist",
     *     joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="artist_id", referencedColumnName="id")}
     * )
     */
	protected $attendingArtists;
	
	public function __construct() {
		$this->attendingArtists = new ArrayCollection();
	}
	
	public function getVenue() {
		return $this->venue;
	}
	
	public function setVenue($venue) {
		$this->venue = $venue;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getDateTime() {
		return $this->datetime;
	}
	
	public function setDateTime($datetime) {
		$this->datetime = $datetime;
	}
	
	public function getAttendingArtists() {
		return $this->attendingArtists;
	}
	
	public function setAttendingArtists($attendingArtists) {
		$this->attendingArtists = $attendingArtists;
	}

}