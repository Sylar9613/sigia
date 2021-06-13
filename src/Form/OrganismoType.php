<?php

namespace App\Form;

use App\Entity\Organismo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrganismoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'70',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,Ú0-9A-Za-zñÑ\s\-]{3,70}',
                    'title'=>'Nombre de cada organismo del sistema',
                    'onblur'=>'validateFields()',
                    'onkeyup'=>'validateFields()',
                    'style'=>'width: 450px;',
                    'tabindex'=>'1',
                    'placeholder'=>'Enter organismo')))
            ->add('controlador', CheckboxType::class,
                array(
                    'label_attr' => array('style' => 'cursor: pointer;'),
                    'attr' => array(
                    'tabindex'=>'2',
                    'style' => 'margin-top: 20px; margin-left: 5px; cursor: pointer;',),
                    'required' => false,));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Organismo::class,
        ]);
    }
}
