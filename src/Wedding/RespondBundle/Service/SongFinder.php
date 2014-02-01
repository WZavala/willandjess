<?php

namespace Wedding\RespondBundle\Service;

use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Guzzle\Http\Exception\BadResponseException;
use Doctrine\ORM\EntityManager;

use Wedding\RespondBundle\Entity\Artist;
use Wedding\RespondBundle\Entity\Song;

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
    
    public function getSong($id)
    {
    
      $results = array();
      
      try {
          $response =  $this->client->get('/lookup?id='.urlencode($id))->send();
      } catch (BadResponseException $e) {
          return $results;
      }
      
      $data = $response->json();
      
      if (empty($data['results'][0])) {
        return $results;
      }
      
      $results = $data['results'][0];
      
      return $results;
            
    }
    
    public function getSaveSongs($ids = array())
    {
    
      foreach ($ids as $id) {
        $this->getSaveSong($id);
      }
            
    }
    
    public function getSaveSong($id)
    {
      
      $artist_repository = $this->em->getRepository('Wedding\RespondBundle\Entity\Artist');
      $song_repository = $this->em->getRepository('Wedding\RespondBundle\Entity\Song');
      
      if ($song_repository->find($id)) {
        return;
      }
      
      $song_info = $this->getSong($id);
      
      if (empty($song_info)) {
        return;
      }
      
      
      $song = new Song();
      $song->setID($song_info['trackId']);
      $song->setTitle($song_info['trackName']);
      
      $artist = $artist_repository->find($song_info['artistId']);
      
      if (!$artist) {
        $artist = new Artist();
        $artist->setID($song_info['artistId']);
        $artist->setName($song_info['artistName']);
      }
      
      $song->setArtist($artist);
      
      $this->em->persist($song);
      
      $this->em->flush();
      
    }
    
}
