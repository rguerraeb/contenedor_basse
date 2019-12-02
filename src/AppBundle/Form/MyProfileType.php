<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\DBAL\Types\TextType;

class MyProfileType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {

        // $data = $this->data;
        $builder
            ->add('name')            
            ->add('firstName')
            ->add('lastName')
          
            ->add('birthdate', 'date',
                array(
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd'
                ))
        // ->add('birthdate', "text")        
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
           
            ->add('address1', "text")
           
            ->add('experienceYears', "text")
            
            
            
           
            
            ;
    }

    /*
     * public $data = array();
     * public function __construct($data) {
     * $this->data = $data; // Now you can use this value while creating a form
     * field for giving any validation.
     * }
     */

    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                array(
                        'data_class' => 'AppBundle\Entity\Staff',
                        'showPointOfSale' => false,
                        "user_role_id" => false
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pre_inscription';
    }
}
