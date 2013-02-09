<?php

namespace Wedding\RespondBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;


class Respond
{

    /**
     * @Assert\NotNull()
     */
    protected $attending;
    
    /**
     * @Assert\NotBlank()
     * @Assert\MaxLength(255)
     */
    protected $name;
    
    /**
     * @Assert\Email()
     * @Assert\MaxLength(255)
     */
    protected $email;
    
    /**
     * @Assert\NotBlank()
     * @Assert\MaxLength(20)
     */
    protected $phone;
    
    /**
     * Songs
     */
    protected $songs;
    
    /**
     * Note
     */
    protected $note;
    
    
    public function setAttending($attending)
    {
        $this->attending = $attending;
    }

    public function getAttending()
    {
        return $this->attending;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }
    
    public function setSongs($songs)
    {
        $this->songs = $songs;
    }

    public function getSongs()
    {
        return $this->songs;
    }
    
    public function setNote($note)
    {
        $this->note = $note;
    }

    public function getNote()
    {
        return $this->note;
    }
    
}