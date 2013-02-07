<?php

namespace Wedding\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    
    public function indexAction()
    {
      return $this->render('WeddingTwitterBundle:Default:index.html.twig');
    }
    
    public function tweetsAction()
    {
      $twitter = $this->get('wedding_twitter.twitter');
      $tweets = $twitter->findSaveTweets('#TheZavalaWedding');
      
      $response = new JsonResponse();
      $response->setData(array(
        'tweets' => $tweets,
      ));
      
      return $response;
      
    }
}
