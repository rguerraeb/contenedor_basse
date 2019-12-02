<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\PromoPrize;

class PromoType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $pointsOfSale = $options['pointsOfSale'];
        $ccs = $options['ccs'];
        $states = $options['states'];
        $cities = $options['cities'];
        $jobPositions = $options['jobPositions'];
        $jp = (count($jobPositions) > 0)?$jobPositions[0]:null;
        $saleChannels = $options['saleChannels'];
        $pPrizes = $options['pPrizes'];
        $promo = $options['data'];
        $parentCategory = $options['parentCategory'];
        $skuCategory = $options['skuCategory'];
        $sku = $options['sku'];

        $builder->add('name')
            ->add(
                'status',
                'choice', 
                array(
                    'choices' => array(
                            "ACTIVE" => "ACTIVA",
                            "INACTIVE" => "INACTIVA"
                    )
                )
            )
            ->add(
                'startDate',
                'date',
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy'
                )
            )
            ->add(
                'endDate',
                'date',
                array(
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy'
                )
            )
            /*
            ->add(
                'cc',
                'entity',
                array(
                    'class' => 'AppBundle:Sku',
                    'choice_value' => 'cc',
                    'choice_label' => 'cc',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->groupBy("u.cc")
                                ->orderBy("u.cc", "ASC");
                        },
                    'expanded' => false,
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,
                    'data' => $ccs
                )
            )
            */
           ->add(
                'skuCategory',
                'entity',
                array(
                        'class' => 'AppBundle:SkuCategory',
                        'choice_value' => 'skuCategoryId',
                        'choice_label' => 'name',
                        'empty_value' => false,
                        'query_builder' => function (
                                \Doctrine\ORM\EntityRepository $er) {
                                    return $er->createQueryBuilder('u')
                                    ->where("u.skuCategoryId <> 4");
                        },
                        'expanded' => false,
                        'multiple' => true,
                        'mapped' => false,
                        'required' => false,
                        'data' => $skuCategory
                        )
                )
                ->add(
                    'sku',
                    'entity',
                    array(
                            'class' => 'AppBundle:Sku',
                            'choice_value' => 'skuId',
                            'choice_label' => 'skuFilterString',
                            'empty_value' => false,
                            'query_builder' => function (
                                    \Doctrine\ORM\EntityRepository $er) {
                                        return $er->createQueryBuilder('u')
                                        ->where("u.skuCategory = 2");
                            },
                            'expanded' => false,
                            'multiple' => true,
                            'mapped' => false,
                            'required' => false,
                            'data' => $sku
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
            ->add(
                'jobPosition',
                'entity',
                array(
                    'class' => 'AppBundle:JobPosition',
                    'empty_value' => false,
                    'expanded' => false,
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false,
                    'choice_value' => 'id',
                    'choice_label' => 'name',
                    'data' => $jp,
                    'query_builder' => function (
                            \Doctrine\ORM\EntityRepository $er)
                    {
                        return $er->createQueryBuilder('u')
                        ->andWhere("u.id != 3")
                        ;
                    }
                )
            )
            ->add(
                'pointOfSale',
                'entity',
                array(
                    'class' => 'AppBundle:PointOfSale',
                    'choice_value' => 'point_of_sale_id',
                    'choice_label' => 'businessName',
                    'empty_value' => '',
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
                    'empty_value' => '',
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
                'promoCategory',
                'entity',
                array(
                    'class' => 'AppBundle:PromoCategory',
                    'choice_label' => 'name',
                    'empty_value' => false,
                    'query_builder' => function (
                        \Doctrine\ORM\EntityRepository $er) {
                            return $er->findByNotSubcategory();
                        },
                    'required' => false,
                    'data' => $parentCategory
                )
            )
            ->add('promoPrizes',
                'collection',
                array(
                    'entry_type' => new PromoPrizeType(),
                    'allow_add' => true,
                    'entry_options' => array(
                        'promoPrizes' => $pPrizes
                    ),
                    'by_reference' => false,
                    'allow_delete' => true,
                    'label' => false
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
                'data_class' => 'AppBundle\Entity\Promo',
                'pointsOfSale' => NULL,
                'states' => NULL,
                'jobPositions' => NULL,
                'cities' => NULL,
                'saleChannels' => NULL,
                'ccs' => NULL,
                'pPrizes' => array(),
                'parentCategory' => NULL,
                'skuCategory' => NULL,
                'sku' => NULL,
            )
        );
    }
   
}
