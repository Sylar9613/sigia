<?php

namespace App\Form;

use App\Entity\TipoAccion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoAccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control col-6',
                    'minlength'=>'2',
                    'maxlength'=>'40',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{2,40}',
                    'title'=>'Nombre de cada tipo de acción',
                    'onblur'=>'validateFields()',
                    'onkeyup'=>'validateFields()',
                    'tabindex'=>'1',
                    'placeholder'=>'Escriba el tipo de acción')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TipoAccion::class,
        ]);
    }
}
