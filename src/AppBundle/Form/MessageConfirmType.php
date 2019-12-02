<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageConfirmType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'sms',
                'hidden',
                array(
                    'required' => true
                )
            )
            ->add(
                'name',
                'hidden',
                array(
                    'required' => true
                )
            )
            ->add(
                'messageType',
                'entity',
                array(
                    'class' => 'AppBundle:MessageType',
                    'required' => true,
                    'attr' => array(
                        'class' => 'hidden'
                    )
                )
            )
            ->add(
                'sendDate',
                'hidden',
                array(
                    'required' => true
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Message',
            'staffs' => array()
        ));
    }
}
