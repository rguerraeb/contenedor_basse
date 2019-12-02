<?php

namespace AppBundle\Form;

use AppBundle\Entity\PurchasedProductDetail;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class RegisterPendingType extends AbstractType
{



    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('pointOfSale', 'entity', 
                array(
						'mapped' => false,
                        'class' => 'AppBundle:PointOfSale',
                        'choice_value' => 'pointOfSaleId',
                        'choice_label' => 'businessName',
                        'empty_data' => true,
                        'empty_value' => "- SELECCIONE -",
                        'required' => false,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            return $er->createQueryBuilder('u')                                                                                                                               
                                ->orderBy("u.businessName", "ASC");
                        }
                ))
            ->add('name')
            ->add('firstName')
            ->add('lastName')            
            ->add('citizenId')
			->add('registerType', 'choice', array('choices' => array("Normal"=>"Normal","Prueba" => "Prueba")))
            ->add('birthdate', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ))
            ->add('phoneMain')
            ->add('phoneSecondary', 'choice', 
                    array('choices' =>
                        array(
                            "Tigo" => "Tigo",
                            "Claro" => "Claro",
                            "Movistar" => "Movistar",
                            "Twenty" => "Twenty"
                        ))
                )
            ->add('email')
            ->add('jobPositionId', 'choice', array('choices' => array(""=>"- SELECCIONE -","1" => "Gerente")))
            ->add('status','hidden')
            ->add('comments')
            ->add('businessCity')
            ->add('businessState')
            ->add('businessZone')
            ->add('businessAddress')
            ->add('groupName')
            ->add('businessName')
            ->add('invoiceNumber')
			->add('cupon')			           
            ->add('profession', 'choice', array('choices' => array(
                ""=>"- SELECCIONE -",
                "BARTENDER" => "BARTENDER",
                "MESERO" => "MESERO",
                "CHEFF" => "CHEFF",                
                "OTRO" => "OTRO",
            )))
            ->add('otherProfession')
            ->add('specialty')
            ->add('experienceYears', "text")                       
            
    ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegisterPending',
        ));
    }
    
}
