<?php

namespace Wedding\TwitterBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * Wedding\TwitterBundle\Entity\TwitterTweetRepository
 *
 */
class TweetRepository extends EntityRepository
{

  public function findMostRecent()
  {
      $em = $this->getEntityManager();
      $query = $em->createQuery('SELECT t FROM WeddingTwitterBundle:Tweet t ORDER BY t.id DESC')->setMaxResults(1);
      
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
      $query = $em->createQuery('SELECT t FROM WeddingTwitterBundle:Tweet t ORDER BY t.created DESC')->setMaxResults($limit);
      
      $result = $query->getResult();
      
      return $result;
      
  }

}