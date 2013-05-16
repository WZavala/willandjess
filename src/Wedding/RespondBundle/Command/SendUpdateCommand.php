<?php

namespace Wedding\RespondBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendUpdateCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this
            ->setName('wedding:sendupdate')
            ->setDescription('Send Update')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    
        // Send the Email to Will & Jess
        $message = \Swift_Message::newInstance();
        $message->setSubject('RSVP');
        
        $from = array(
           'william.b.zavala@gmail.com' => 'William Zavala',
        );
        
        
        $message->setFrom($from);
        
        $params = array();
        
        $templating = $this->getContainer()->get('templating');
        
        $text = $templating->render('WeddingRespondBundle:Default:update.txt.twig', $params);
        
        $message->setBody($text);
        
        $mailer = $this->getContainer()->get('mailer');
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $repository = $this->getContainer()->get('doctrine')->getRepository('Wedding\RespondBundle\Entity\RSVP');
        
        $attending = $repository->findByAttending(1);
        
        $sent = 0;
                
        foreach ($attending as $rsvp) {
        
          $to = array(
            $rsvp->getEmail() => $rsvp->getName(),
          );
          
          $message->setTo($to);
          $sent += $mailer->send($message);
          
        }
                
        $transport = $mailer->getTransport();
        if (!$transport instanceof \Swift_Transport_SpoolTransport) {
            return;
        }
        
        $spool = $transport->getSpool();
        if (!$spool instanceof \Swift_MemorySpool) {
            return;
        }
        
        $spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));
        
        $output->writeln('Sent '.$sent.' messages');
        
    }
}