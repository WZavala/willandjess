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
          
          $from = array(
            $rsvp->getEmail() => $rsvp->getName(),
          );
          
          $message->setFrom($from);
          
          $to = array(
            'william.b.zavala@gmail.com' => 'William Zavala',
            'cjessicaucf@knights.ucf.edu' => 'Jessica Collier',
          );
          
          $message->setTo($to);
          
          $params = array(
            'rsvp' => $rsvp,
            'songs' => $songs,
          );
          
          $text = $this->renderView('WeddingRespondBundle:Default:email.txt.twig', $params);
          
          $message->setBody($text);
          
          $this->get('mailer')->send($message);
          
          if ($request->isXmlHttpRequest()) {
          
            $data = array(
              'title' => 'Thanks!',
              'content' => $this->renderView('WeddingRespondBundle:Default:thanks.html.twig'),
            );
            
            $response = new JsonResponse();
            $response->setData($data);
            
            return $response;
            
          }
          
          // Set the Message
          $this->get('session')->getFlashBag()->add('message', 'Thanks!');
          
          // Redirect back to the homaepage
          return $this->redirect($this->generateUrl('wedding_respond_homepage'));
          
        }
        else {
          
          if ($request->isXmlHttpRequest()) {
          
            $errors = array();
          
            foreach ($form->all() as $child) {
              foreach ($child->getErrors() as $error) {
                $errors[] = array(
                  'id' => $form->getName().'_'.$child->getName(),
                  'text' => $error->getMessage(),
                );
              }
            }
          
            $data = array(
              'errors' => $errors,
            );
            
            $response = new JsonResponse();
            $response->setData($data);
            
            return $response;
          
          }
          
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
    
    public function thanksAction(Request $request)
    {
      
      return $this->render('WeddingRespondBundle:Default:thanks.html.twig');
      
    }
    
    public function peopleAction(Request $request, $who)
    {
    
      $title = '';
      $people = array();
      
      if ($who == 'ladies') {
        
        $title = 'Ladies';
        
        $people = array(
          'jess' => array(
            'image' => 'http://lorempixel.com/250/250/cats/?v=jess',
            'name' => 'Jessica Collier',
            'title' => 'Bride',
            'desc' => "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>",
            'social' => array(
              'facebook' => 'jessica.collier.99',
              'twitter' => 'JCol99',
            ),
          ),
        );
        
      }
      elseif ($who == 'gentlemen') {
        
        $title = 'Gentlemen';
        
        $people = array(
          'will' => array(
            'image' => 'http://lorempixel.com/250/250/cats/?v=will',
            'name' => 'William Zavala',
            'title' => 'Groom',
            'desc' => "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>",
            'social' => array(
              'facebook' => 'Alavaz',
              'twitter' => 'FunnyMSB',
            ),
          ),
          'david' => array(
            'image' => '/bundles/weddingrespond/images/people/david.jpg',
            'name' => 'David Barratt',
            'title' => 'Groomsman',
            'desc' => "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>",
            'social' => array(
              'facebook' => 'davidbarratt',
              'twitter' => 'davidwbarratt',
            ),
          ),
          'josh' => array(
            'image' => 'http://lorempixel.com/250/250/cats/?v=josh',
            'name' => 'Josh Shearer',
            'title' => 'Groomsman',
            'desc' => "<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>",
            'social' => array(
              'facebook' => 'graciaman',
              'twitter' => 'graciaman',
            ),
          ),
        );
        
        
      }
      
      $params = array(
        'title' => $title,
        'people' => $people,
      );
            
      return $this->render('WeddingRespondBundle:Default:people.html.twig', $params);
      
    }
    
    public function registryAction(Request $request)
    {
      
      return $this->render('WeddingRespondBundle:Default:registry.html.twig');
      
    }
    
    public function travelAction(Request $request)
    {
      
      return $this->render('WeddingRespondBundle:Default:travel.html.twig');
      
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
