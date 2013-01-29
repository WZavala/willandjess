<?php

namespace WillAndJess\Bundle\RespondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
      return $this->render('WillAndJessRespondBundle:Default:index.html.twig');
    }
    
    public function tweetsAction()
    {
      $twitter = $this->get('will_and_jess_respond.twitter');
      $tweets = $twitter->search('#TheZavalaWedding');
      
      $response = new JsonResponse();
      $response->setData(array(
        'tweets' => $tweets,
      ));
      
      return $response;
      
    }
    
}
