<?php

namespace App\Form;

use App\Entity\Situacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SituacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'label'=>'Situación',
                'attr'=>array(
                    'class'=>'form-control',
                    'style'=>'width: 350px;',
                    'minlength'=>'3',
                    'maxlength'=>'80',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,80}',
                    'title'=>'Nombre de cada situación',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'tabindex'=>'1',
                    'placeholder'=>'Escriba la situación')))
            ->add('fecha', DateType::class,
                array('label'=>'Fecha de emisión de la situación',
                    'attr'=>array(
                        'tabindex'=>'2',
                        'title'=>'Fecha de emisión de la situación',
                        'onblur'=>'validateFields("2")',
                        'onkeyup'=>'validateFields("2")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('emisor', TextType::class, array(
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'style'=>'width: 300px;',
                    'maxlength'=>'30',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{,80}',
                    'title'=>'Emisor de cada situación',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'tabindex'=>'3',
                    'placeholder'=>'Escriba el emisor')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Situacion::class,
        ]);
    }
}
