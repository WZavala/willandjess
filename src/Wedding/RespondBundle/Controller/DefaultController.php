<?php

namespace Wedding\RespondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
      return $this->render('WeddingRespondBundle:Default:index.html.twig');
    }
    
}
