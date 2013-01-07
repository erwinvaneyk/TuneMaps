<?php

namespace TuneMaps\MusicDataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A song
 * 
 * @ORM\Entity
 * @ORM\Table(name="song")
 */
class Song {
    
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
     * The song's title
     * 
	 * @ORM\Column(type="string", length=255) 
     * 
     * @var string
	 */
	protected $title;
	
	/**
     * The artist that produced this song
     * 
	 * @ORM\ManyToOne(targetEntity="Artist", cascade={"persist"})
     * 
     * @var Artist
	 */
	protected $artist;
    
    /**
     * The song's image URL
     * 
     * @ORM\Column(type="string", length=255)
     * 
     * @var string
     */
    protected $image;
    
    /**
     * The song in youtube
     * 
     * @ORM\Column(type="string", length=40)
     * 
     * @var string
     */
    protected $youtube;
    
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
     * Gets the title
     * 
     * @return string title
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Sets the title
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Gets the artist
     * 
     * @return Artist artist
     */
    public function getArtist() {
        return $this->artist;
    }
    
    /**
     * Sets the artist
     * 
     * @param Artist $artist
     */
    public function setArtist($artist) {
        $this->artist = $artist;
    }
    
    /**
     * Gets the image
     * 
     * @return string image
     */
    public function getImage() {
        return $this->image;
    }
    
    /**
     * Sets the image
     * 
     * @param string $image
     */
    public function setImage($image) {
        $this->image = $image;
    }
    
    /**
     * Gets the youtube
     * 
     * @return string youtube
     */
    public function getYoutube() {
        return $this->youtube;
    }
    
    /**
     * Sets the youtube
     * 
     * @param string $youtube
     */
    public function setYoutube($youtube) {
        $this->youtube = $youtube;
    }
    
}
