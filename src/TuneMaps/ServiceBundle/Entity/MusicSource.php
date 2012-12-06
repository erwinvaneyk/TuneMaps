<?php
namespace TuneMaps\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity
* @ORM\Table(name="tunemaps_musicsource")
*/
class MusicSource implements \JsonSerializable 
{
    
     /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $rank;
    
    /**
    * @ORM\OneToOne(targetEntity="\TuneMaps\RecommendationBundle\Entity\Song")
    */
    protected $song;
    
    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $uri;
    
    /**
    * @ORM\Column(type="string", length=255)
    */
    protected $type;
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function setRank($rank) {
        $this->rank = $rank;
    }
    
    public function getRank() {
        return $this->rank;
    }
    
    public function setSong($song) {
        $this->song = $song;
    }
    
    public function getSong() {
        return $this->song;
    }
    
    public function setUri($uri) {
        $this->uri = $uri;
    }
    
    public function getUri() {
        return $this->uri;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}
?>
