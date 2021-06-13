<?php

namespace App\Form;

use App\Entity\Entidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'3',
                    'maxlength'=>'70',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,Ú0-9A-Za-zñÑ\s\-\.]{3,70}',
                    'title'=>'Nombre de cada entidad del sistema',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'tabindex'=>'1',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Enter entidad')))
            ->add('osde', EntityType::class, array(
                'class' => 'App\Entity\Osde',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->where('o.activo=1')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array('class'=>'form-control', 'style'=>'width: 450px;', 'tabindex'=>'4')
            ))
            ->add('nit', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'11',
                    'maxlength'=>'11',
                    'pattern'=>'[0-9\s\-\.]{11}',
                    'title'=>'Número de Identificación Tributaria de cada entidad del sistema',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'tabindex'=>'2',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Enter NIT'),
                'label'=>'Número de Identificación Tributaria (NIT)'))
            ->add('reeup', TextType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'minlength'=>'11',
                    'maxlength'=>'11',
                    'pattern'=>'[0-9\s\-\.]{11}',
                    'title'=>'Registro de Entidades Provincial de cada entidad del sistema',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'tabindex'=>'3',
                    'style'=>'width: 450px;',
                    'placeholder'=>'Enter REEUP'),
                'label'=>'Registro de Entidades Provincial (REEUP)'))
            ->add('ai', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'5',
                    'style'=>'margin-top: 20px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'Auditor interno (AI)',
                'label_attr'=>array('style'=>'cursor: pointer;')))
            ->add('uai', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'6',
                    'style'=>'margin-top: 15px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'Unidad de auditoría interna (UAI)',
                'label_attr'=>array('style'=>'cursor: pointer;')))
            ->add('ucai', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'7',
                    'style'=>'margin-top: 15px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'Unidad de auditoría interna del CAP (UCAI)',
                'label_attr'=>array('style'=>'cursor: pointer;')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entidad::class,
        ]);
    }
}