<?php

namespace App\Form;

use App\Entity\Implicado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImplicadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cargo', TextType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'padding: 0; margin-right:395px;',
                ),
                'attr'=>array(
                    'class'=>'form-control float-right',
                    'minlength'=>'3',
                    'maxlength'=>'200',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,200}',
                    'title'=>'Cargo que ocupa',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'tabindex'=>'14',
                    'style'=>'width: 450px; margin-top: 28px; margin-right:-450px;',
                    'placeholder'=>'Entre cargo ocupado')))
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'200',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,200}',
                    'title'=>'Nombre y apellidos de cada implicado',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'tabindex'=>'13',
                    'style'=>'width: 400px;',
                    'placeholder'=>'Entre nombre completo')))
            ->add('categoriaOcupacional', TextType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'padding: 0; margin-right:280px; margin-top:20px;',
                ),
                'label'=>'Categoría ocupacional',
                'attr'=>array(
                    'class'=>'form-control float-right',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,50}',
                    'title'=>'Categoría ocupacional de cada implicado',
                    'onblur'=>'validateFields("4")',
                    'onkeyup'=>'validateFields("4")',
                    'tabindex'=>'16',
                    'style'=>'width: 450px; margin-top: 48px; margin-right:-452px;',
                    'placeholder'=>'Entre categoría ocupacional')))
            ->add('escolaridad', TextType::class, array(
                'label_attr'=>array(
                    'style'=>'margin-top:20px;',
                ),
                'label'=>'Nivel de escolaridad',
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,50}',
                    'title'=>'Nivel de escolaridad de cada implicado',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'tabindex'=>'15',
                    'style'=>'width: 400px;',
                    'placeholder'=>'Entre nivel de escolaridad')))
            ->add('edad', IntegerType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'margin-top:20px; margin-right:190px;',
                ),
                'attr'=>array(
                    'class'=>'form-control float-right',
                    'min'=>'16',
                    'max'=>'90',
                    'pattern'=>'[0-9]{1,2}',
                    'title'=>'Edad de cada implicado',
                    'onblur'=>'validateFields("7")',
                    'onkeyup'=>'validateFields("7")',
                    'tabindex'=>'19',
                    'style'=>'width: 160px; margin-top: 48px; margin-right:-160px;',
                    'placeholder'=>'Entre edad')))
            ->add('sexo', TextType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'margin-top:20px; margin-right:165px;',
                ),
                'attr'=>array(
                    'class'=>'form-control float-right',
                    'minlength'=>'1',
                    'maxlength'=>'1',
                    'pattern'=>'[m,f,M,F]{1}',
                    'title'=>'Sexo de cada implicado',
                    'onblur'=>'validateFields("6")',
                    'onkeyup'=>'validateFields("6")',
                    'tabindex'=>'18',
                    'style'=>'width: 140px; margin-top: 48px; margin-right:-140px;',
                    'placeholder'=>'Entre sexo')))
            ->add('nivelDireccion', TextType::class, array(
                'label_attr'=>array(
                    'style'=>'margin-top:20px;',
                ),
                'label'=>'Nivel de dirección',
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,50}',
                    'title'=>'Nivel de dirección de cada implicado',
                    'onblur'=>'validateFields("5")',
                    'onkeyup'=>'validateFields("5")',
                    'tabindex'=>'17',
                    'style'=>'width: 400px;',
                    'placeholder'=>'Entre nivel de dirección')))
            ->add('pcc', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'20',
                    'style'=>'margin-top: 20px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'PCC',
                'label_attr'=>array('style'=>'cursor: pointer;')))
            ->add('ujc', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'21',
                    'style'=>'margin-top: 15px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'UJC',
                'label_attr'=>array('style'=>'cursor: pointer;')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Implicado::class,
        ]);
    }
}
