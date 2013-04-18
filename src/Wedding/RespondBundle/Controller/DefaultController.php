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
          
          
          // Send the Email to Will & Jess
          $message = \Swift_Message::newInstance();
          $message->setSubject('RSVP');
          
          $from = array(
            $rsvp->getEmail() => $rsvp->getName(),
          );
          
          $message->setFrom($from);
          
          $bridegroom = array(
            'william.b.zavala@gmail.com' => 'William Zavala',
            'cjessicaucf@knights.ucf.edu' => 'Jessica Collier',
          );
          
          $message->setTo($bridegroom);
          
          $params = array(
            'rsvp' => $rsvp,
            'songs' => $songs,
          );
          
          $text = $this->renderView('WeddingRespondBundle:Default:email.txt.twig', $params);
          
          $message->setBody($text);
          
          $this->get('mailer')->send($message);
          
          // Send the Email to the User
          $title = ($rsvp->getAttending()) ? 'Yay! :)' : 'Aww! :(';
          
          $message = \Swift_Message::newInstance();
          $message->setSubject($title);
          $message->setFrom($bridegroom);
          $message->setTo($rsvp->getEmail());
          
          $params = array(
            'attending' => $rsvp->getAttending(),
          );
          
          $content = $this->renderView('WeddingRespondBundle:Default:thanks.html.twig', $params);
          
          $message->setBody($content, 'text/html');
          
          $this->get('mailer')->send($message);
          
          
          if ($request->isXmlHttpRequest()) {
          
            $data = array(
              'title' => $title,
              'content' => $content,
            );
            
            $response = new JsonResponse();
            $response->setData($data);
            
            return $response;
            
          }
          
          // Set the Message
          if ($rsvp->getAttending()) {
            $this->get('session')->getFlashBag()->add('message', $title);
          }
          else {
            $this->get('session')->getFlashBag()->add('notice', $title);
          }
                    
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
    
    public function thanksAction(Request $request, $attending = TRUE)
    {
      
      $params = array(
        'attending' => $attending,
      );
      
      return $this->render('WeddingRespondBundle:Default:thanks.html.twig', $params);
      
    }
    
    public function peopleAction(Request $request, $who)
    {
    
      $title = '';
      $people = array();
      
      if ($who == 'ladies') {
        
        $title = 'Ladies';
        
        $people = array(
          'jennifer' => array(
            'name' => 'Jennifer Black',
            'image' => 'http://lorempixel.com/250/250/cats/?v=jennifer',
            'title' => 'Maid of Honor',
            'desc' => $this->renderView('WeddingRespondBundle:People:jennifer.html.twig'),
            'social' => array(),
          ),
          'jocelyn' => array(
            'name' => 'Jocelyn Hofstede ',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:jocelyn.html.twig'),
            'social' => array(
              'facebook' => 'jocelyn.davidson.1',
            ),
          ),
          'paige' => array(
            'image' => '/bundles/weddingrespond/images/people/paige.jpg',
            'name' => 'Paige Warga',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:paige.html.twig'),
            'social' => array(
              'facebook' => 'paige.warga'
            ),
          ),
          'kristen' => array(
            'image' => '/bundles/weddingrespond/images/people/kristen.jpg',
            'name' => 'Kristen Hicks',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:kristen.html.twig'),
            'social' => array(
              'facebook' => 'kristen.hicks.395',
            ),
          ),
          'dina' => array(
            'image' => '/bundles/weddingrespond/images/people/dina.jpg',
            'name' => 'Dina Kennedy',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:dina.html.twig'),
            'social' => array(
              'facebook' => 'dinak90',
            ),
          ),
          'alyssa' => array(
            'name' => 'Alyssa Boddie',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:alyssa.html.twig'),
            'social' => array(
              'facebook' => 'alyssa.boddie',
            ),
          ),
          'nicole' => array(
            'name' => 'Alyssa Boddie',
            'title' => 'Bridesmaid',
            'desc' => $this->renderView('WeddingRespondBundle:People:nicole.html.twig'),
            'social' => array(
              'facebook' => 'nicole.joseph.792',
            ),
          ),
        );
        
      }
      elseif ($who == 'gentlemen') {
        
        $title = 'Gentlemen';
        
        $people = array(
          'matt' => array(
            'name' => 'Matt Shuler',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:matt.html.twig'),
            'social' => array(
              'facebook' => 'shulermatt',
            ),
          ),
          'andrew' => array(
            'image' => '/bundles/weddingrespond/images/people/andrew.jpg',
            'name' => 'Andrew Tungate',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:andrew.html.twig'),
            'social' => array(
              'facebook' => 'atungate',
              'twitter' => 'andrewstungate',
            ),
          ),
          'jonathan' => array(
            'name' => 'Jonathan Goodwin',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:jonathan.html.twig'),
            'social' => array(
              'facebook' => 'jon.j.goodwin'
            ),
          ),
          'david' => array(
            'name' => 'David Barratt',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:david.html.twig'),
            'social' => array(
              'facebook' => 'davidbarratt',
              'twitter' => 'davidwbarratt',
            ),
          ),
          'brandon' => array(
            'name' => 'Brandon Davis',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:brandon.html.twig'),
            'social' => array(
              'facebook' => '100001487503542'
            ),
          ),
          'josh' => array(
            'name' => 'Josh Shearer',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:josh.html.twig'),
            'social' => array(
              'facebook' => 'graciaman',
              'twitter' => 'graciaman',
            ),
          ),
          'matt_f' => array(
            'name' => 'Matt Furlong',
            'title' => 'Groomsman',
            'desc' => $this->renderView('WeddingRespondBundle:People:matt_f.html.twig'),
            'social' => array(
              'facebook' => 'mattfurlong11',
              'twitter' => 'ucfmatt',
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
