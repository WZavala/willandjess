<?php

namespace WillAndJess\Bundle\RespondBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Exception\BadResponseException;

class Twitter {
    
    protected $client;
         
    public function __construct($consumer_key, $consumer_secret, $access_key, $access_secret)
    {
    
        $this->client = new Client('https://api.twitter.com/1.1'); 
        $oauth = new OauthPlugin(array(
          'consumer_key' => $consumer_key,
          'consumer_secret' => $consumer_secret,
          'token' => $access_key,
          'token_secret' => $access_secret,
        ));
        $this->client->addSubscriber($oauth);
        
    }
    
    public function search($query = '')
    {
    
      $tweets = array();
      
      try {
          $response = $this->client->get('search/tweets.json?include_entities=true&q='.urlencode($query))->send();
      } catch (BadResponseException $e) {
          return $tweets;
      }
      
      $data = $response->json();
      
      if (empty($data['statuses'])) {
        return $tweets;
      }
      
      $statuses = $data['statuses'];
      
      foreach ($statuses as $status) {
      
        $tweet = $status['text'];
        
        if (substr($tweet, 0, 2) == 'RT') {
          continue;
        }
        
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
            'replacement' => '<a href="https://twitter.com/'.$user_mention['screen_name'].'">@'.$user_mention['screen_name'].'</a>'
          );
          
        }
        
        krsort($entities);
        
        foreach ($entities as $entity) {
          
          $tweet = substr_replace($tweet, $entity['replacement'], $entity['start'], $entity['length']);
          
        }
        
        $tweet = '<p class="tweet"><span class="text">'.$tweet.'</span> <span class="from">&mdash;<a href="http://twitter.com/'.$status['user']['screen_name'].'">@'.$status['user']['screen_name'].'</a></span></p>';
        
        $tweets[] = $tweet;
        
      }
      
      return $tweets;
      
    }
    
}
