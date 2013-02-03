<?php

namespace Wedding\RespondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
      return $this->render('WeddingRespondBundle:Default:index.html.twig');
    }
    
    public function tweetsAction()
    {
      $twitter = $this->get('wedding_respond.twitter');
      $tweets = $twitter->findSaveTweets('#TheZavalaWedding');
      
      $response = new JsonResponse();
      $response->setData(array(
        'tweets' => $tweets,
      ));
      
      return $response;
      
    }
    
}
