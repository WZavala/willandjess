<?php

namespace Wedding\RespondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity
 */
class Song
{
    /**
     * @var integer
     *
     * @ORM\Column(name="song_id", type="bigint")
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Artist", cascade={"persist"})
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="artist_id")
     */
    private $artist;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\ManyToMany(targetEntity="RSVP", mappedBy="song")
     **/
    private $rsvp;
    
    /**
     * Construct
     */
    public function __construct()
    {
        $this->rsvp = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Song
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
     * Set artist
     *
     * @param \Wedding\RespondBundle\Entity\Artist $artist
     * @return Artist
     */
    public function setArtist(\Wedding\RespondBundle\Entity\Artist $artist)
    {
        $this->artist = $artist;
    
        return $this;
    }

    /**
     * Get artist
     *
     * @return integer 
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Song
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Add rsvp
     *
     * @param \Wedding\RespondBundle\Entity\RSVP $rsvp
     * @return Song
     */
    public function addRSVP(\Wedding\RespondBundle\Entity\RSVP $rsvp)
    {
        $this->rsvp[] = $rsvp;
    
        return $this;
    }

    /**
     * Remove rsvp
     *
     * @param \Wedding\RespondBundle\Entity\RSVP $rsvp
     */
    public function removeRSVP(\Wedding\RespondBundle\Entity\RSVP $rsvp)
    {
        $this->rsvp->removeElement($rsvp);
    }

    /**
     * Get rsvp
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRSVP()
    {
        return $this->rsvp;
    }
    
}
