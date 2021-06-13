<?php

namespace App\Form;

use App\Entity\Nivel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NivelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array(
            'attr'=>array(
                'class'=>'form-control',
                'minlength'=>'3',
                'maxlength'=>'100',
                'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,Ú0-9A-Za-zñÑ\s\-\.]{3,100}',
                'title'=>'Nombre de cada nivel escolar',
                'onblur'=>'validateFields()',
                'onkeyup'=>'validateFields()',
                'tabindex'=>'1',
                'style'=>'width: 550px;',
                'placeholder'=>'Escriba nivel escolar')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Nivel::class,
        ]);
    }
}
