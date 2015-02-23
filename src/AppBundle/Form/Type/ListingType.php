<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ListingType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){
         $builder
            ->add(
                'Title',
                'text',
                [
                    'attr'       => ['class' => 'field-class', 'maxlength' => '35'],
                    'label_attr' => ['class' => 'label-class badge'],
                    'label'      => 'title'

                ]
            )

            ->add(
                'Body',
                'textarea',
                [
                    'attr'       => ['class' => 'field-class field-body', 'maxlength' => '1000'],
                    'label_attr' => ['class' => 'label-class badge'],
                    'label'      => 'body'

                ]
            )

            ->add(
                'price',
                'number',
                [
                    'attr'       => ['class' => 'field-class', 'maxlength' => '10'],
                    'label_attr' => ['class' => 'label-class badge'],
                    'label'      => 'price'

                ]
            )

            ->add(
                'document',
                new DocumentType(),
                [
                    'attr'       => ['class' => 'field-class'],
                    'label_attr' => ['class' => 'label-class badge'],
                    'label'      => 'image'
                ]
            )

            ->add(
                'save',
                'submit',
                [
                    'attr'       => ['class' => 'button-class'],
                    'label'      => 'create listing'
                ]
            );
    }

    public function getName()
    {
        return 'listing';
    }

}