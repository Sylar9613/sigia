<?php

namespace App\Form;

use App\Entity\CausaCondicion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CausaCondicionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control col-6',
                    'minlength'=>'3',
                    'maxlength'=>'80',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,80}',
                    'title'=>'Nombre de cada causa/condición por la que se pueden dar los PHD',
                    'onblur'=>'validateFields()',
                    'onkeyup'=>'validateFields()',
                    'tabindex'=>'1',
                    'placeholder'=>'Escriba el nombre')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CausaCondicion::class,
        ]);
    }
}
