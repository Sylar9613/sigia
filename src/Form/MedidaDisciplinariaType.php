<?php

namespace App\Form;

use App\Entity\MedidaDisciplinaria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedidaDisciplinariaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoria', TextType::class, array(
                'label'=>'Categoría',
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'5',
                    'maxlength'=>'40',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{5,40}',
                    'title'=>'Categoría de cada medida disciplinaria',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'tabindex'=>'1',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Entre categoría')))
            ->add('nombre', TextType::class, array(
                'label'=>'Medida disciplinaria',
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'5',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{5,50}',
                    'title'=>'Nombre de la medida disciplinaria',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'tabindex'=>'2',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Entre nombre')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MedidaDisciplinaria::class,
        ]);
    }
}
