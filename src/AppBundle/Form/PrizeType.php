<?php

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PrizeType extends AbstractType
{
  	/**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    
	    $object = $builder->getData();
        if ($object->getId()) {
            // Has id, it's editing
            $options = array(
                    'required' => false,
                    'data_class' => null
            );
        } else {
            $options = array(
                    'required' => true,
                    'data_class' => null
            );
        }


        $builder
            ->add('amount')
            ->add('imagePath', FileType::class, $options )
            ->add('displayName')
            ->add('value')
            ->add('smsResponse')
            ->add('instructions')
            ->add('isActive', 'choice', array('choices' => array("1" => "ACTIVO", "0" => "INACTIVO")))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Prize'
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'site_content';
    }

}
