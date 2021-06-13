<?php

namespace App\Form;

use App\Entity\Particularidad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticularidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'70',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,70}',
                    'title'=>'Nombre de cada particularidad',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'tabindex'=>'1',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Escriba particularidad')))
            ->add('siglas', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'1',
                    'maxlength'=>'5',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{1,5}',
                    'title'=>'Siglas de cada particularidad',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'tabindex'=>'2',
                    'style'=>'width: 200px;',
                    'placeholder'=>'Escriba siglas')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Particularidad::class,
        ]);
    }
}
