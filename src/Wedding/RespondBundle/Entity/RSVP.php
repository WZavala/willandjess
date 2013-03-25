<?php

namespace Wedding\RespondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tweet
 *
 * @ORM\Table(name="rsvp")
 * @ORM\Entity
 */
class RSVP
{
    /**
     * @var integer
     *
     * @ORM\Column(name="rsvp_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="attending", type="boolean")
     */
    private $attending;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="adults", type="integer")
     */
    private $adults;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="children", type="integer")
     */
    private $children;
    
    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Song", inversedBy="rsvp")
     * @ORM\JoinTable(name="rsvp_song",
     *      joinColumns={@ORM\JoinColumn(name="rsvp_id", referencedColumnName="rsvp_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="song_id", referencedColumnName="song_id")}
     * )
     **/
    private $song;


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
     * Set attending
     *
     * @param boolean $attending
     * @return RSVP
     */
    public function setAttending($attending)
    {
        $this->attending = $attending;
    
        return $this;
    }

    /**
     * Get attending
     *
     * @return boolean 
     */
    public function getAttending()
    {
        return $this->attending;
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return RSVP
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
     * Set email
     *
     * @param string $email
     * @return RSVP
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set phone
     *
     * @param string $phone
     * @return RSVP
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * Set adults
     *
     * @param int $adults
     * @return RSVP
     */
    public function setAdults($adults)
    {
        $this->adults = $adults;
    
        return $this;
    }

    /**
     * Get adults
     *
     * @return int
     */
    public function getAdults()
    {
        return $this->adults;
    }
    
    /**
     * Set children
     *
     * @param int $children
     * @return RSVP
     */
    public function setChildren($children)
    {
        $this->children = $children;
    
        return $this;
    }

    /**
     * Get children
     *
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set note
     *
     * @param string $note
     * @return RSVP
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }
    
     /**
     * Add song
     *
     * @param \Wedding\RespondBundle\Entity\Song $song
     * @return RSVP
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
