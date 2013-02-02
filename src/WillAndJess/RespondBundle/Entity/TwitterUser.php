<?php

namespace WillAndJess\RespondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tweet
 *
 * @ORM\Table(name="twitter_user")
 * @ORM\Entity
 */
class TwitterUser
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
     * @ORM\OneToMany(targetEntity="TwitterTweet", mappedBy="tweet", cascade={"all"})
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
     * @return TwitterUser
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
     * @return TwitterUser
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
     * @param \WillAndJess\RespondBundle\Entity\TwitterTweet $tweet
     * @return Place
     */
    public function addTweet(\WillAndJess\RespondBundle\Entity\TwitterTweet $tweet)
    {
        $this->tweet[] = $tweet;
    
        return $this;
    }

    /**
     * Remove tweet
     *
     * @param \WillAndJess\RespondBundle\Entity\TwitterTweet $tweet
     */
    public function removeTweet(\WillAndJess\RespondBundle\Entity\TwitterTweet $tweet)
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
