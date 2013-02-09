<?php

namespace Wedding\TwitterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tweet
 *
 * @ORM\Table(name="twitter_user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="bigint")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;
    
    /**
     * @ORM\OneToMany(targetEntity="Tweet", mappedBy="user", cascade={"all"})
     */
    private $tweet;
    
    /**
     * Construct
     */
    public function __construct()
    {
        $this->tweet = new ArrayCollection();
    }
    
    /**
     * Set id
     *
     * @param integer $id
     * @return User
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Add tweet
     *
     * @param \Wedding\TwitterBundle\Entity\Tweet $tweet
     * @return Place
     */
    public function addTweet(\Wedding\TwitterBundle\Entity\Tweet $tweet)
    {
        $this->tweet[] = $tweet;
    
        return $this;
    }

    /**
     * Remove tweet
     *
     * @param \Wedding\TwitterBundle\Entity\Tweet $tweet
     */
    public function removeTweet(\Wedding\TwitterBundle\Entity\Tweet $tweet)
    {
        $this->tweet->removeElement($tweet);
    }

    /**
     * Get tweet
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTweet()
    {
        return $this->tweet;
    }
    
}
