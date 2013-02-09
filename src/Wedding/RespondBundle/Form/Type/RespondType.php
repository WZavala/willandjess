<?php

namespace Wedding\RespondBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RespondType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    
        $builder->add('attending', 'choice', array(
          'choices' => array(
            1 => "I'm crashing your wedding. (Yes)",
            0 => "I have to wash my hair that day. Sorry. (No)"
          ),
          'expanded' => TRUE,
          'label'  => 'Coming to Celebrate?',
        ));
        $builder->add('name', 'text');
        $builder->add('email', 'email');
        $builder->add('phone', 'text');
        $builder->add('songs', 'text', array(
          'label' => 'Like to Dance? (So do we)',
          'required' => FALSE,
        ));
        $builder->add('note', 'textarea');
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wedding\RespondBundle\Form\Model\Respond'
        ));
    }

    public function getName()
    {
        return 'respond';
    }
}