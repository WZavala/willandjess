<?php

namespace Wedding\RespondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tweet
 *
 * @ORM\Table(name="artist")
 * @ORM\Entity
 */
class Artist
{
    /**
     * @var integer
     *
     * @ORM\Column(name="artist_id", type="bigint")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Song", mappedBy="id", cascade={"all"})
     */
    private $song;
    
    /**
     * Construct
     */
    public function __construct()
    {
        $this->song = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param integer $id
     * @return Artist
     */
    public function setID($id)
    {
        $this->id = $id;
    
        return $this;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Artist
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Add Song
     *
     * @param \Wedding\RespondBundle\Entity\Song $song
     * @return Artist
     */
    public function addSong(\Wedding\RespondBundle\Entity\Song $song)
    {
        $this->song[] = $song;
    
        return $this;
    }

    /**
     * Remove song
     *
     * @param \Wedding\RespondBundle\Entity\Song $song
     */
    public function removeSong(\Wedding\RespondBundle\Entity\Song $song)
    {
        $this->song->removeElement($song);
    }

    /**
     * Get song
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSong()
    {
        return $this->song;
    }
    
}
