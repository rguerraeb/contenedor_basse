<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageStaffType extends AbstractType
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
            ->add(
                'sms',
                'textarea',
                array(
                    'required' => true
                )
            )
            ->add(
                'state',
                'entity',
                array(
                    'class' => 'AppBundle:State',
                    'choice_value' => 'state_id',
                    'choice_label' => 'name',
                    'empty_value' => "Todos",
                    'mapped' => false,
                    'required' => false,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        }
                )
            )
            ->add(
                'city',
                'entity',
                array(
                    'class' => 'AppBundle:City',
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'empty_value' => "Todos",
                    'mapped' => false,
                    'required' => false,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        }
                )
            )
            ->add(
                'saleChannel',
                'entity',
                array(
                    'class' => 'AppBundle:SaleChannel',
                    'choice_value' => 'sale_channel_id',
                    'choice_label' => 'name',
                    'empty_value' => "Todos",
                    'mapped' => false,
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        },
                )
            )
            ->add(
                'pointOfSale',
                'entity',
                array(
                    'class' => 'AppBundle:PointOfSale',
                    'choice_value' => 'point_of_sale_id',
                    'choice_label' => 'businessName',
                    'empty_value' => "Todos",
                    'mapped' => false,
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('pos')
                                ->orderBy("pos.businessName", "ASC");
                        },
                )
            )
            ->add(
                'jobPosition',
                'entity',
                array(
                    'class' => 'AppBundle:JobPosition',
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'empty_value' => "Todos",
                    'mapped' => false,
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('j')
                                ->orderBy("j.name", "ASC");
                        },
                )
            )
            ->add('name')
            ->add(
                'messageType',
                'entity',
                array(
                    'class' => 'AppBundle:MessageType',
                    'choice_value' => 'messageTypeId',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'required' => true
                )
            )
            ->add(
                'sendDate',
                'hidden',
                array(
                    'required' => false
                )
            )
            ->add('zone',
                'choice',
                array(
                    'choices' => $zones,
                    'required' => false,
                    'empty_value' => 'Todas',
                    'mapped' => false,
                    'expanded' => false,
                    'multiple' => false
                )
            )
            ->add('sendDateDate',
                'text',
                array(
                    'required' => false,
                    'mapped' => false,
                )
            )
            ->add('sendDateTime',
                'text',
                array(
                    'required' => false,
                    'mapped' => false,
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
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }
}
