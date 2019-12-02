<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // To have data selected
        $pointsOfSale = $options['pointsOfSale'];
        $states = $options['states'];
        $cities = $options['cities'];
        $jobPositions = $options['jobPositions'];
        $saleChannels = $options['saleChannels'];

        $builder
            ->add(
                'name',
                null,
                array(
                    'required' => true
                )
            )
            ->add('description')
            ->add(
                'goalStatus',
                'entity',
                array(
                    'class' => 'AppBundle:GoalStatus',
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'mapped' => true,
                    'required' => true,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('gs')
                                ->where('gs.id != 3');
                        }
                )
            )
            ->add(
                'start',
                'date',
                array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
                )
            )
            ->add(
                'end',
                'date',
                array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
                )
            )
            ->add(
                'goalType',
                'entity',
                array(
                    'class' => 'AppBundle:GoalType',
                    'choice_value' => 'goalTypeId',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'mapped' => true,
                    'required' => true
                )
            )
            ->add(
                'prize',
                'entity',
                array(
                    'class' => 'AppBundle:Prize',
                    'choice_value' => 'id',
                    'choice_label' => 'displayName',
                    'empty_value' => false,
                    'mapped' => true,
                    'required' => false,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->where('p.isActive = 1')
                                ->orderBy('p.order');
                        }
                )
            )
            ->add(
                'points',
                null,
                array(
                    'required' => false
                )
            )
            ->add(
                'message',
                'text',
                array(
                    'required' => false
                )
            )
            ->add(
                'quantity',
                'number'
            )
            ->add(
                'jobPosition',
                'entity',
                array(
                    'class' => 'AppBundle:JobPosition',
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'mapped' => false,
                    'required' => true,
                    'expanded' => false,
                    'multiple' => true,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('jp')
                                ->where('jp.id != 3')
                                ->andWhere('jp.id != 1');
                        },
                    'data' => $jobPositions
                )
            )
            ->add(
                'pointOfSale',
                'entity',
                array(
                    'class' => 'AppBundle:PointOfSale',
                    'choice_value' => 'point_of_sale_id',
                    'choice_label' => 'businessName',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.businessName", "ASC");
                        },
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'data' => $pointsOfSale
                )
            )
            ->add(
                'saleChannel',
                'entity',
                array(
                    'class' => 'AppBundle:SaleChannel',
                    'choice_value' => 'sale_channel_id',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        },
                    'required' => false,
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'data' => $saleChannels
                )
            )
            ->add(
                'state',
                'entity',
                array(
                    'class' => 'AppBundle:State',
                    'choice_value' => 'state_id',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        },
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,
                    'data' => $states
                )
            )
            ->add(
                'city',
                'entity',
                array(
                    'class' => 'AppBundle:City',
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->orderBy("u.name", "ASC");
                        },
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,
                    'data' => $cities
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
            'data_class' => 'AppBundle\Entity\Goal',
            'pointsOfSale' => array(),
            'states' => array(),
            'jobPositions' => array(),
            'cities' => array(),
            'saleChannels' => array()
        ));
    }
}
