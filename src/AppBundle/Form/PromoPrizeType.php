<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\PromoPrize;

class PromoPrizeType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $promoPrizes = $options['promoPrizes'];

        $builder
            ->add(
                'promoPrizeType',
                'entity',
                array(
                    'class' => 'AppBundle:PromoPrizeType',
                    'empty_value' => false,
                    'label' => 'Tipo de premio',
                    'attr' => array(
                        'class' => 'prize-type form-control'
                    )
                )
            )
            ->add(
                'points',
                'integer',
                array(
                    'label' => 'Puntos',
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => '1',
                        'max' => '9999'
                    )
                )
            )
            ->add(
                'factor',
                'text',
                array(
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => '0',
                        'max' => '9999'
                    )
                )
            )
            ->add(
                'name',
                'text',
                array(
                    'label' => 'Premio específico',
                    'attr' => array(
                        'class' => 'form-control'
                    )
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
                    'required' => false,
                    'query_builder' =>
                        function (\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->where('p.isActive = 1')
                                ->orderBy('p.orderNumber');
                        },
                    'label' => 'Premio de catálogo',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )
            ->add(
                'notificationMessage',
                'textarea',
                array(
                    'label' => 'Mensaje para notificación de premio',
                    'attr' => array(
                        'class' => 'form-control notification-message',
                        'maxLength' => 240,
                        'minLength' => 1
                    )
                )
            )
            ->add(
                'probability',
                'number',
                array(
                    'label' => 'Probabilidad',
                    'attr' => array(
                        'class' => 'form-control probability',
                        'min' => '0',
                        'max' => '1'
                    )
                )
            )
            ->add(
                'maxQuantity',
                'integer',
                array(
                    'label' => 'Máximo de premios por ganador',
                    'attr' => array(
                        'class' => 'form-control max-quantity',
                        'min' => 0,
                        'max' => 9999
                    )
                )
            )
        ;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\PromoPrize',
                'promoPrizes' => array(),
            )
        );
    }
   
}
