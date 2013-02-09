<?php

namespace Wedding\RespondBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Wedding\RespondBundle\Entity\RSVP;
use Wedding\RespondBundle\Entity\Song;
use Wedding\RespondBundle\Form\Type\RespondType;
use Wedding\RespondBundle\Form\Model\Respond;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
      // Build the Registration Form
      $form = $this->createForm(new RespondType(), new Respond());
      
      // If this Form has been completed
      if ($request->isMethod('POST')) {
      
        // Bind the Form to the request
        $form->bind($request);
        
        // Check to make sure the form is valid before procceding
        if ($form->isValid()) {
          
          $respond = $form->getData();
          
          $song_repository = $this->getDoctrine()->getRepository('Wedding\RespondBundle\Entity\Song');
          
          $song_list = $respond->getSongList();
          $song_ids = explode(',', $song_list);
          
          $song_finder = $this->get('wedding_respond.songfinder');
          $song_finder->getSaveSongs($song_ids);
          
          $rsvp = new RSVP();
          $rsvp->setAttending($respond->getAttending());
          $rsvp->setName($respond->getName());
          $rsvp->setEmail($respond->getEmail());
          $rsvp->setPhone($respond->getPhone());
          $rsvp->setNote($respond->getNote());
                    
          $songs = $song_repository->findById($song_ids);
          
          if (!empty($songs)) {
            foreach ($songs as $song) {
              $rsvp->addSong($song);
            }
          }
            
          
          $em = $this->getDoctrine()->getManager();
          
          $em->persist($rsvp);
          $em->flush();
                              
          return $this->redirect($this->generateUrl('wedding_respond_homepage'));
        }
      
      }
      
      $params = array(
        'form' => $form->createView(),
      );
      
      return $this->render('WeddingRespondBundle:Default:index.html.twig', $params);
    }
    
    public function songsAction(Request $request)
    {
      
      $song_finder = $this->get('wedding_respond.songfinder');
      $songs = $song_finder->findSongs($request->get('q'));
      
      $response = new JsonResponse();
      $response->setData($songs);
      
      return $response;
      
    }
    
}
