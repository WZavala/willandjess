<?php

namespace Wedding\RespondBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Exception\BadResponseException;
use Doctrine\ORM\EntityManager;

class SongFinder {
    
    protected $em;
    
    protected $client;
         
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        
        $this->client = new Client('https://itunes.apple.com'); 
    }
    
    public function findSongs($query = '')
    {
    
      $results = array();
      
      try {
          $response =  $this->client->get('search?media=music&entity=song&limit=10&term='.urlencode($query))->send();
      } catch (BadResponseException $e) {
          return $results;
      }
      
      $data = $response->json();
      
      if (empty($data['results'])) {
        return $results;
      }
      
      foreach ($data['results'] as $result) {
        $results[] = array(
          'id' => $result['trackId'],
          'name' => $result['trackName'].' - '.$result['artistName'],
        );
      }
      
      return $results;
            
    }
    
}
