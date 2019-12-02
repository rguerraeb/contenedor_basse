<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\DBAL\Types\FloatType;

class PurchasedProductDetailType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',IntegerType::class,
                array(
                    'label' => 'Cantidad',
                    'attr' => array(
                        'class' => 'form-control',
                        'min' => 0,
                        'max' => 9999
                    )
                )
            )            
	        ->add('value','Symfony\Component\Form\Extension\Core\Type\TextType',
		        array(
			        'label' => 'Valor',
			        'attr' => array(
				        'class' => 'form-control',
				        'min' => 0
			        )
		        )
	        )
	        ->add('purchasedProductCategory','Symfony\Bridge\Doctrine\Form\Type\EntityType',
		        array(
			        'class' => 'AppBundle\Entity\PurchasedProductCategory',
			        'attr' => array(
				        'class' => 'form-control',
			        ),
			        'choice_value' => 'purchasedProductCategoryId',
			        'choice_label' => 'name',
			        'empty_value' => "- SELECCIONE -",
			        'mapped' => true,
			        'required' => true,
			        'query_builder' =>
				        function (\Doctrine\ORM\EntityRepository $er) {
					        return $er->createQueryBuilder('s')
					                  ->where('1=1')
					                  ->orderBy("s.name", "ASC");
				        }
		        ))
	        ->add('purchasedProductList','Symfony\Bridge\Doctrine\Form\Type\EntityType',
		        array(
			        'class' => 'AppBundle\Entity\PurchasedProductList',
			        'attr' => array(
				        'class' => 'form-control',
			        ),
			        'choice_value' => 'purchasedProductListId',
			        'choice_label' => 'name',
			        'empty_value' => "- SELECCIONE -",
			        'mapped' => true,
			        'required' => true,
			        'query_builder' =>
				        function (\Doctrine\ORM\EntityRepository $er) {
					        return $er->createQueryBuilder('s')
					                  ->where('1=1')
					                  ->orderBy("s.name", "ASC");
				        }
		        ))

        ;

/*        $builder->get('purchasedProductList')
	        ->addModelTransformer(new CallbackTransformer(
		        function ($tagsAsArray) {
			        // transform the array to a string
			        return implode(', ', $tagsAsArray);
		        },
		        function ($tagsAsString) {
			        // transform the string back to an array
			        return explode(', ', $tagsAsString);
		        }
	        ))  ;*/

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
                'data_class' => 'AppBundle\Entity\PurchasedProductDetail',
            )
        );
    }


	public function getName()
	{
		return 'ppdt';
	}

}
