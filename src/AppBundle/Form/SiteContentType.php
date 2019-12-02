<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SiteContentType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        // If has id means it's editing
        $object = $builder->getData();
        if ($object->getId()) {
            // Has id, it's editing
            $options = array(
                    'required' => false
            );
        } else {
            $options = array(
                    'required' => true
            );
        }

        $builder->add('title')
            ->add('duration')
            ->add('contentType', 'choice',
                array(
                        'choices' => array(
                                "NOTICIA" => "NOTICIA",
                                "PROMOCION" => "PROMOCION",
                                "EVENTO" => "EVENTO"
                        )
                ))
            ->add('content')
            ->add('imageFile', 'file', $options)
            ->add('publicationDate', DateTimeType::class,
            [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'input'  => 'datetime',
				]);
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
                        'data_class' => 'AppBundle\Entity\SiteContent'
		) );
	}

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'site_content';
    }


}
