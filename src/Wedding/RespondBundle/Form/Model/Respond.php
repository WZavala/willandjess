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
     * @Assert\Length(max="255")
     */
    protected $name;

    /**
     * @Assert\Email()
     * @Assert\Length(max="255")
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="20")
     */
    protected $phone;

    /**
     * Adults
     *
     * @Assert\Type(type="numeric")
     */
    protected $adults;

    /**
     * Children
     *
     * @Assert\Type(type="numeric")
     */
    protected $children;

    /**
     * Song List
     */
    protected $song_list;

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

    public function setAdults($adults)
    {
        $this->adults = $adults;
    }

    public function getAdults()
    {
        return $this->adults;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setSongList($song_list)
    {
        $this->song_list = $song_list;
    }

    public function getSongList()
    {
        return $this->song_list;
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
