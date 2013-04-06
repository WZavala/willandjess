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
          $rsvp->setAdults($respond->getAdults());
          $rsvp->setChildren($respond->getChildren());
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
          
          
          // Send the Email
          $message = \Swift_Message::newInstance();
          $message->setSubject('RSVP');
          $message->setFrom(array($rsvp->getEmail() => $rsvp->getName()));
          $message->setTo(array('william.b.zavala@gmail.com' => 'William Zavala'));
          
          $params = array(
            'rsvp' => $rsvp,
            'songs' => $songs,
          );
          
          $text = $this->renderView('WeddingRespondBundle:Default:email.txt.twig', $params);
          
          $message->setBody($text);
          
          $this->get('mailer')->send($message);
          
          return $this->redirect($this->generateUrl('wedding_respond_homepage'));
          
        }
      
      }
      
      $kernel = $this->get('kernel');
      $path = $kernel->locateResource('@WeddingRespondBundle/Resources/public/images/photos/');
      
      $dir = opendir($path);
      
      $photos = array();
      
      while ($filename = readdir($dir)) {
        if (substr($filename, 0, 1) != '.') {
          $photos[] = $filename;
        }
      }
      
      $params = array(
        'photos' => $photos,
        'form' => $form->createView(),
      );
      
      return $this->render('WeddingRespondBundle:Default:index.html.twig', $params);
      
    }
    
    public function gentlemenAction(Request $request)
    {
      
      $params = array();
      
      return $this->render('WeddingRespondBundle:Default:gentlemen.html.twig', $params);
      
    }
    
    public function ladiesAction(Request $request)
    {
      
      $params = array();
      
      return $this->render('WeddingRespondBundle:Default:ladies.html.twig', $params);
      
    }
    
    public function registryAction(Request $request)
    {
      
      $params = array();
      
      return $this->render('WeddingRespondBundle:Default:registry.html.twig', $params);
      
    }
    
    public function travelAction(Request $request)
    {
      
      $params = array();
      
      return $this->render('WeddingRespondBundle:Default:travel.html.twig', $params);
      
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
