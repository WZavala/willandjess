<?php

namespace WillAndJess\Bundle\RespondBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * WillAndJess\Bundle\RespondBundle\Entity\TwitterTweetRepository
 *
 */
class TwitterTweetRepository extends EntityRepository
{

  public function findMostRecent()
  {
      $em = $this->getEntityManager();
      $query = $em->createQuery('SELECT t FROM WillAndJessRespondBundle:TwitterTweet t ORDER BY t.id DESC')->setMaxResults(1);
      
      try {
        $result = $query->getSingleResult();
      }
      catch (NoResultException $e) {
        $result = FALSE;
      }
      
      return $result;
      
  }
  
  public function findRecent($limit = 15)
  {
      $em = $this->getEntityManager();
      $query = $em->createQuery('SELECT t FROM WillAndJessRespondBundle:TwitterTweet t ORDER BY t.created DESC')->setMaxResults($limit);
      
      $result = $query->getResult();
      
      return $result;
      
  }

}