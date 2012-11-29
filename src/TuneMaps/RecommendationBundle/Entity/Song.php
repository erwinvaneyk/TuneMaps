<?php

namespace TuneMaps\RecommendationBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tunemaps_song")
 */
class Song
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=40)
     */
    protected $mbid;

}