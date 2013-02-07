<?php

namespace Wedding\TwitterBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Exception\BadResponseException;
use Doctrine\ORM\EntityManager;

use Wedding\TwitterBundle\Entity\Tweet;
use Wedding\TwitterBundle\Entity\User;

class Twitter {
    
    protected $em;
    
    protected $client;
         
    public function __construct(EntityManager $em, $consumer_key, $consumer_secret, $access_key, $access_secret)
    {
        
        $this->em = $em;
        
        $this->client = new Client('https://api.twitter.com/1.1'); 
        $oauth = new OauthPlugin(array(
          'consumer_key' => $consumer_key,
          'consumer_secret' => $consumer_secret,
          'token' => $access_key,
          'token_secret' => $access_secret,
        ));
        $this->client->addSubscriber($oauth);
        
    }
    
    public function findSaveTweets($query = '')
    {
    
      $repository = $this->em->getRepository('Wedding\TwitterBundle\Entity\Tweet');
      $latest = $repository->findMostRecent();
      $latest_id = ($latest) ? $latest->getID() : 0;
      $statuses = $this->findTweets($query, $latest_id);
      $this->saveTweets($statuses);
      
      $tweets = array();
      
      if ($recent = $repository->findRecent()) {
        
        foreach ($recent as $tweet) {
          $tweets[] = '<p class="tweet"><span class="text">'.$tweet->getText().'</span> <span class="from">&mdash;<a href="http://twitter.com/'.$tweet->getUser()->getUsername().'">@'.$tweet->getUser()->getUsername().'</a></span></p>';
        }
        
      }
      
      return $tweets;
  
    }
    
    public function findTweets($query = '', $since_id = 0)
    {
    
      $statuses = array();
      
      try {
          $response = $this->client->get('search/tweets.json?include_entities=true&count=100&since_id='.$since_id.'&q='.urlencode($query))->send();
      } catch (BadResponseException $e) {
          return $statuses;
      }
      
      $data = $response->json();
            
      if (empty($data['statuses'])) {
        return $statuses;
      }
      
      $statuses = $data['statuses'];
      
      return $statuses;
      
    }
    
    public function saveTweets($statuses = array())
    {
      
      $repository = $this->em->getRepository('Wedding\TwitterBundle\Entity\User');
      
      foreach ($statuses as $status) {
      
        if (substr($status['text'], 0, 2) == 'RT') {
          continue;
        }
        
        $tweet = new Tweet();
        $tweet->setID($status['id']);
        $tweet->setCreated($status['created_at']);
        $tweet->setText($this->formatTweet($status));
                
        if (!$user = $repository->find($status['user']['id'])) {
          $user = new User();
          $user->setID($status['user']['id']);
          $user->setUsername($status['user']['screen_name']);
        }
        
        $tweet->setUser($user);
        
        $this->em->persist($tweet);
        
        $this->em->flush();
        
      }
      
    }
    
    public function formatTweet($status)
    {
      
      $tweet = $status['text'];
      
      $entities = array();
      
      foreach ($status['entities']['hashtags'] as $hashtag) {
      
        $entities[$hashtag['indices'][0]] = array(
          'start' => $hashtag['indices'][0] ,
          'length' => strlen('#'.$hashtag['text']),
          'replacement' => '<a href="https://twitter.com/search?q='.urlencode('#'.$hashtag['text']).'">#'.$hashtag['text'].'</a>',
        );
        
      }
      
      foreach ($status['entities']['urls'] as $url) {
      
        $entities[$url['indices'][0]] = array(
          'start' => $url['indices'][0],
          'length' => strlen($url['url']),
          'replacement' => '<a href="'.$url['expanded_url'].'">'.$url['display_url'].'</a>',
        );
        
      }
      
      foreach ($status['entities']['user_mentions'] as $user_mention) {
      
        $entities[$user_mention['indices'][0]] = array(
          'start' => $user_mention['indices'][0],
          'length' => strlen('@'.$user_mention['screen_name']),
          'replacement' => '<a href="https://twitter.com/'.$user_mention['screen_name'].'">@'.$user_mention['screen_name'].'</a>',
        );
        
      }
      
      krsort($entities);
      
      foreach ($entities as $entity) {
        
        $tweet = substr_replace($tweet, $entity['replacement'], $entity['start'], $entity['length']);
        
      }    
    
      return $tweet;
      
    }
    
}
