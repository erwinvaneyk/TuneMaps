<?php
namespace TuneMaps\RecommendationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="tunemaps_songplayed")
*/
class ArtistPlayed 
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\TuneMaps\UserBundle\Entity\User")
     */
    private $user;
    
    /**
     * @ORM\id
     * @ORM\ManyToOne(targetEntity="Artist")
     */
    private $artist;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $timesPlayed;
    
    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $lastPlayed;
    
    public function __construct($userId, $artistId) {
        $this->user = $userId;
        $this->artist = $artistId;
        $this->timesPlayed = 1;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function getArtist() {
        return $this->artist;
    }
    
    public function getTimesPlayed() {
        return $this->timesPlayed;
    }
    
    public function getLastPlayed() {
        return $this->lastPlayed;
    }
    
    public function setartist($artist) {
        $this->artist = $artist;
    }
    
    public function setUser($user) {
        $this->user = $user;
    }
    
    public function setTimesPlayed($times) {
        $this->timesPlayed = $times;
    }
    
    public function setLastPlayed($datetime) {
        $this->lastPlayed = $datetime;
    }
    
    public function stampLastPlayed() {
        $this->lastPlayed = new \DateTime();
    }
    
    public function incTimesPlayed($add = 1) {
        $this->timesPlayed += $add;
    }
        
    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}
?>
