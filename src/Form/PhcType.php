<?php

namespace App\Form;

use App\Entity\Phc;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', IntegerType::class, array(
                'label' => 'Número de Expediente',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'1',
                    'min'=>'1',
                    'max'=>'1000',
                    'pattern'=>'[0-9\s\-\/\.]{1,7}',
                    'title'=>'Número de Expediente',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'style'=>'width:20%;')))
            ->add('categoria', TextType::class, array(
                'label'=>'Categoría (OACE/Entidad Nacional/CAP)',
                'attr'=>array(
                    'tabindex'=>'2',
                    'title'=>'Categoría de la entidad (OACE/Entidad Nacional/CAP)',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,50}',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'style'=>'width: 400px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre categoría')))
            ->add('provincia', TextType::class, array(
                'label'=>'Provincia',
                'attr'=>array(
                    'tabindex'=>'3',
                    'title'=>'Provincia',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,50}',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'style'=>'width: 250px;',
                    'class'=>'form-control',
                    'value'=>'Matanzas',
                    'placeholder'=>'Matanzas')))
            ->add('fuente', TextType::class, array(
                'label'=>'Fuente o vía de detección',
                'attr'=>array(
                    'tabindex'=>'4',
                    'title'=>'Fuente o vía de detección del PHC',
                    'minlength'=>'3',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,50}',
                    'onblur'=>'validateFields("4")',
                    'onkeyup'=>'validateFields("4")',
                    'style'=>'width: 410px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre fuente de detección')))
            ->add('fechaDeteccion', DateType::class,
                array('label'=>'Fecha de detección del PHC',
                    'attr'=>array(
                        'tabindex'=>'5',
                        'title'=>'Fecha de detección del PHC',
                        'onblur'=>'validateFields("5")',
                        'onkeyup'=>'validateFields("5")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('fechaOcurrencia', DateType::class,
                array('label'=>'Fecha de ocurrencia del PHC',
                    'attr'=>array(
                        'tabindex'=>'6',
                        'title'=>'Fecha de ocurrencia del PHC',
                        'onblur'=>'validateFields("6")',
                        'onkeyup'=>'validateFields("6")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('resumen', TextareaType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'9',
                    'cols'=>'45',
                    'rows'=>'3',
                    'title'=>'Resumen del PHC',
                    'minlength'=>'1',
                    'maxlength'=>'65525',
                    'onblur'=>'validateFields("9")',
                    'aria-required'=>'true',
                    'placeholder'=>'Entre el resumen')))
            ->add('entidad', EntityType::class, array(
                'class' => 'App\Entity\Entidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.activo=1')
                        ->orderBy('e.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'7',
                    'title'=>'Todas las entidades del sistema',
                    'style'=>'width: 400px;',
                    'class'=>'form-control')
            ))
            ->add('municipio', EntityType::class, array(
                'class' => 'App\Entity\Municipio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.activo=1')
                        ->orderBy('m.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'8',
                    'title'=>'Todos los municipios de Matanzas',
                    'style'=>'width: 240px;',
                    'class'=>'form-control')
            ))
            ->add('accionControl', EntityType::class, array(
                'label' => 'Orden de trabajo',
                'class' => 'App\Entity\AccionControl',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.activo=1')
                        ->orderBy('a.ordenTrabajo', 'ASC');
                },
                'choice_label' => 'ordenTrabajo',
                'placeholder' => 'Acciones de control',
                'required' => false,
                'attr'=>array(
                    'tabindex'=>'9',
                    'style'=>'width:200px',
                    'title'=>'Todas las acciones de control insertadas',
                    'class'=>'form-control')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phc::class,
        ]);
    }
}
