<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoicePendingType extends AbstractType
{

    /**
     *
     * {@inheritdoc}
     */
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder->add('invoiceImage')
            ->add('invoiceStatus', 'hidden')
            ->add("comments")
            ->add("invoiceNumber", "text")
            ->add('invoiceDate', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd'
            ));
    }

    /**
     *
     * {@inheritdoc}
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                array(
                        'data_class' => 'AppBundle\Entity\InvoicePending'
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_invoicepending';
    }


}
