<?php

namespace WillAndJess\Bundle\RespondBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 *
 * @ORM\Table(name="twitter_tweet")
 * @ORM\Entity(repositoryClass="WillAndJess\Bundle\RespondBundle\Entity\TwitterTweetRepository")
 */
class TwitterTweet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="tweet_id", type="bigint")
     * @ORM\Id
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="TwitterUser", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    
    /**
     * Set id
     *
     * @param integer $id
     * @return TwitterTweet
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
     * Set user
     *
     * @param \WillAndJess\Bundle\RespondBundle\Entity\TwitterUser $user
     * @return TwitterTweet
     */
    public function setUser(\WillAndJess\Bundle\RespondBundle\Entity\TwitterUser $user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return TwitterTweet
     */
    public function setText($text)
    {
        $this->text = $text;
    
        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Tweet
     */
    public function setCreated($created)
    {
        $this->created = new \DateTime($created);
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
    
}
