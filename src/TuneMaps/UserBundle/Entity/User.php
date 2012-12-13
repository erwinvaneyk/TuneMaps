<?php

namespace TuneMaps\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use TuneMaps\RecommendationBundle\Entity\Location;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_user")
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
    * @ORM\OneToOne(targetEntity="\TuneMaps\RecommendationBundle\Entity\Location")
    */
    protected $lastLocation;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $age;
    
    /**
     * @ORM\Column(type="string", length=1) 
     */
    protected $gender;
    
    /**
     * @ORM\column(type="integer")
     */
    protected $playcount = 0;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getGender() {
        return $this->gender;
    }
    
    public function getLastLocation() {
        return $this->lastLocation;
    }
    
    public function getPlayCount() {
        return $this->playcount;
    }
    
    public function setGender($gender) {
        if($gender != 'm' && $gender != 'f')
            throw new \Exception('Invalid gender provided: ' + $gender);
        else
            $this->gender = $gender;
    }
    
    public function setLocation(Location $loc) {
        $this->lastLocation = $loc;
    }
    
    public function setPlayCount($count) {
        if($count < 0) $count = 0;
        $this->playcount = $count;
    }
    
    public function incPlayCount($add = 1) {
        $this->playcount += $add;
    }
}