<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
         $builder
            ->add(
                'file',
                'file',
                [
                    'label' => ' '
                ]
            );
    }

    public function getName()
    {
        return 'document';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\Document'
            ]
        );
    }
}