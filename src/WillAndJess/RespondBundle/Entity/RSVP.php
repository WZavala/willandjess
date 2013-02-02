<?php

namespace WillAndJess\RespondBundle\Entity;

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
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;


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
    
    
}
