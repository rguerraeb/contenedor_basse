<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointOfSaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Build zones array
        $zones = array();
        for ($i=0; $i < 26; $i++) { 
            $zones[$i] = $i;
        }

        $builder
            ->add('groupName', "text")
            ->add('businessName', "text")
            ->add('taxIdentifier',
                null,
                array(
                    'required' => true
                )
            )
            
            ->add('pointOfSaleType')
            ->add('homePhone')            
            ->add('version')
            ->add('address1', "text")
            ->add('address2',
                'choice',
                array(
                    'choices' => $zones,
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false
                )
            )
            ->add('pointOfSaleInnerId',
                null,
                array(
                    'required' => true
                )
            )
            ->add('status',
                'choice',
                array(
                    'choices' => array(
                        '1' => 'Activo',
                        '0' => 'Inactivo'
                    ),
                    'required' => true,
                    'expanded' => false,
                    'multiple' => false
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
            'data_class' => 'AppBundle\Entity\PointOfSale'
        ));
    }
}
