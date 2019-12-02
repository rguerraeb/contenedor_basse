<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\DBAL\Types\TextType;

class PreInscriptionType extends AbstractType
{
    
    private $jps = 0;

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        
        $this->jps = $options["showAllJobPositions"];

        // $data = $this->data;
        $builder
            ->add('name')
            ->add('firstName')
            ->add('lastName')
            ->add('taxIdentifier')
            ->add('citizenId')
            ->add('gender', 'choice',
                array(
                        'choices' => array(
                                "" => "- SELECCIONE -",
                                "M" => "Masculino",
                                "F" => "Femenino"
                        )
                ))
            ->add('birthdate', 'date',
                array(
                        'widget' => 'single_text',
                        'format' => 'yyyy-MM-dd'
                ))
            ->
        // ->add('birthdate', "text")
        add('phoneMain')
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
            ->add('state', 'entity',
                array(
                        'class' => 'AppBundle:State',
                        'choice_value' => 'stateId',
                        'choice_label' => 'name',
                        'empty_value' => "- SELECCIONE -",
                        'mapped' => true,
                        'required' => true,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            return $er->createQueryBuilder('s')
                                ->where('1=1')
                                ->orderBy("s.name", "ASC");
                        }
                ))
            ->add('city', 'entity',
                array(
                        'class' => 'AppBundle:City',
                        'choice_value' => 'id',
                        'choice_label' => 'name',
                        'empty_value' => "- SELECCIONE -",
                        'mapped' => true,
                        'required' => true,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            return $er->createQueryBuilder('s')
                                ->where('1=1')
                                ->orderBy("s.name", "ASC");
                        }
                ))
            ->add('country', 'entity',
                array(
                        'class' => 'AppBundle:Country',
                        'choice_value' => 'id',
                        'choice_label' => 'name',
                        'empty_value' => false,
                        'mapped' => true,
                        'required' => true,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            return $er->createQueryBuilder('c')
                                ->where('c.id = 1');
                        }
                ))
            ->add('address1', "text")
            ->add('zone', "text")
            ->add('jobPosition', 'entity',
                array(
                        'class' => 'AppBundle:JobPosition',
                        'choice_value' => 'id',
                        'choice_label' => 'name',
                        'empty_value' => "- SELECCIONE -",                        
                        'mapped' => true,
                        'required' => true,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            if ($this->jps) {
                                return $er->createQueryBuilder('jp')
                                ->where("1=1")
                                    ;
                            } else {
                                return $er->createQueryBuilder('jp')
                                ->where("jp.id not in (3)")
                                ;
                            }
                        }
                ))
                ->add('profession', 'choice', array('choices' => array(
                        ""=>"- SELECCIONE -",
                        "BARTENDER" => "BARTENDER",
                        "MESERO" => "MESERO",
                        "CHEFF" => "CHEFF",
                        "OTRO" => "OTRO",
                )))
            ->add('otherProfession')
            ->add('specialty')
            
            
            ->add('childNum', 'choice',
                array(
                        'choices' => array(
                                "0" => "- NINGUNO -",
                                "1" => "1",
                                "2" => "2",
                                "3" => "3",
                                "4" => "4",
                                "5" => "5",
                                "6" => "6",
                                "7" => "7",
                                "8" => "8",
                                "9" => "9",
                                "10" => "10",
                                "11" => "MÁS DE 10"
                        )
                ))
            ->add('childAge', "text")
            ->add('religion', 'choice',
                array(
                        'choices' => array(
                                "" => "- SELECCIONE -",
                                "CRISTIANO" => "CRISTIANO",
                                "CATOLICO" => "CATOLICO",
                                "EVANGELICO" => "EVANGELICO",
                                "NINGUNA" => "NINGUNA",
                                "OTRA" => "OTRA"
                        )
                ))
            ->add('phoneThrid', "text", array(
                'required' => false
        ))
            ->add('social', 'choice',
                array(
                        'choices' => array(
                                "" => "- SELECCIONE -",
                                "FACEBOOK" => "FACEBOOK",
                                "TWITTER" => "TWITTER",
                                "INSTAGRAM" => "INSTAGRAM",
                                "OTRA" => "OTRA"
                        )
                ))
            ->add('freeTime', 'choice',
                array(
                        'choices' => array(
                                "" => "- SELECCIONE -",
                                "FUTBOL" => "FUTBOL",
                                "VISITAR FAMILIARES" => "VISITAR FAMILIARES",
                                "OTROS DEPORTES" => "OTROS DEPORTES",
                                "ASISTIR A LA IGLESIA" => "ASISTIR A LA IGLESIA",
                                "ACTIVIDADES AL AIRE LIBRE" => "ACTIVIDADES AL AIRE LIBRE",
                                "DESCANSAR" => "DESCANSAR",
                                "CINE" => "CINE",
                                "ESCUCHAR MUSICA" => "ESCUCHAR MUSICA",
                                "COMIDA EN FAMILIA" => "COMIDA EN FAMILIA",
                                "VER TV" => "VER TV",
                                "OTRO" => "OTRO"
                        )
                ))
            ->add('staffStatus', 'entity',
                array(
                        'class' => 'AppBundle:StaffStatus',
                        'choice_value' => 'id',
                        'choice_label' => 'name',
                        'empty_value' => false,
                        'mapped' => true,
                        'required' => true,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er)
                        {
                            return $er->createQueryBuilder('ss')
                                ->where('ss.id != 3')
                                ->orderBy("ss.name", "ASC");
                        }
                ));
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
                        "user_role_id" => false,
                        "showAllJobPositions" => false
                ));
    }
}
