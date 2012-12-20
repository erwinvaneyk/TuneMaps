<?php
namespace TuneMaps\RecommendationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
* @ORM\Entity
* @ORM\Table(name="tunemaps_songplayed")
*/
class SongPlayed implements \JsonSerializable 
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\TuneMaps\UserBundle\Entity\User")
     */
    private $user;
    
    /**
     * @ORM\id
     * @ORM\ManyToOne(targetEntity="Song")
     */
    private $song;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $timesPlayed;
    
    /**
     * @ORM\Column(type="datetime", columnDefinition="TIMESTAMP DEFAULT CURRENT_TIMESTAMP")
     */
    private $lastPlayed;
    
    public function __construct($userId, $songId) {
        $this->user = $userId;
        $this->song = $songId;
        $this->timesPlayed = 1;
    }
    
    public function getUser() {
        return $this->user;
    }
    
    public function getSong() {
        return $this->song;
    }
    
    public function getTimesPlayed() {
        return $this->timesPlayed;
    }
    
    public function getLastPlayed() {
        return $this->lastPlayed;
    }
    
    public function setSong($song) {
        $this->song = $song;
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
