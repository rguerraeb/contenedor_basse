<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffPointOfSaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $staffPointsOfSale = $options['staffPointsOfSale'];
        $builder
            ->add('pointOfSale',
                'entity',
                array(
                    'class' => 'AppBundle:PointOfSale',
                    'query_builder' => function($repository) use ($staffPointsOfSale) {
                        $qb = $repository->createQueryBuilder('pos');
                        // the function returns a QueryBuilder object
                        // Find all active point of sale
                        $qb
                            ->where('1 = 1');

                        // If its in attribute, has to be in options
                        if ($staffPointsOfSale) {
                            $qb->andWhere("pos.pointOfSaleId not in ($staffPointsOfSale)");
                        }

                        return $qb;
                    },
                    'label' => 'Punto de Venta',
                    'attr' => array(
                        'class' => 'form-control'
                    )
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
            'data_class' => 'AppBundle\Entity\StaffPointOfSale',
            'staffPointsOfSale' => null
        ));
    }
}
