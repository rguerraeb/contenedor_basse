<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\DistributorRepository;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password', 'password', 
                array(
                    'required' => false
                ))
            ->add('status', 'choice', array('choices' => array("ACTIVO" => "ACTIVO", "INACTIVO" => "INACTIVO")))
            ->add('userRole')
            ->add('distributor',EntityType::class,array('class' => 'AppBundle:Distributor',
            'required' => false,
            'placeholder' => '',
            'query_builder' => function (DistributorRepository $sts) {
                                 return $sts->createQueryBuilder('d') 
                                 ->where('d.status = :active')
                                 ->setParameter('active','ACTIVO')
                                 ;
                       }                                                          
               ));	
				
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}
