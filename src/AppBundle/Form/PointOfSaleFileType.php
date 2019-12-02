<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointOfSaleFileType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('file', 'file',
                array(
                    'label' => 'Punto de venta'
                )
            )
            ->add('save', 'submit',
                array(
                    'attr' => array(
                        'class' => 'btn btn-rounded btn-success'
                    ),
                    'label' => 'Iniciar carga'
                )
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PointOfSale',
        ));
    }
}