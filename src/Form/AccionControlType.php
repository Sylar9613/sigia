<?php

namespace App\Form;

use App\Entity\AccionControl;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccionControlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordenTrabajo', TextType::class, array(
                'label'=>'Orden de trabajo',
                'attr'=>array(
                    'tabindex'=>'1',
                    'title'=>'Orden de trabajo de cada acción de control',
                    'minlength'=>'1',
                    'maxlength'=>'10',
                    'pattern'=>'[d,e,l0-9\s\-]{1,10}',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'style'=>'width: 200px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre orden')))
            ->add('directivas', TextType::class, array(
                'required'=>false,
                'attr'=>array(
                    'tabindex'=>'2',
                    'title'=>'Directivas de cada acción de control',
                    'minlength'=>'1',
                    'maxlength'=>'50',
                    'pattern'=>'[0-9\x3B\s\-]{1,50}',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'style'=>'width: 200px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre directivas')))
            ->add('auditorPlan', IntegerType::class, array(
                'label' => 'Auditor(P)',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'8',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de auditores planificados para la acción de control',
                    'onblur'=>'validateFields("4")',
                    'onkeyup'=>'validateFields("4")',
                    'style'=>'width:120px;')))
            ->add('auditorReal', IntegerType::class, array(
                'label' => 'Auditor(R)',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'11',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de auditores reales que intervinieron en la acción de control',
                    'onblur'=>'validateFields("7")',
                    'onkeyup'=>'validateFields("7")',
                    'style'=>'width:120px;')))
            ->add('diasPlan', IntegerType::class, array(
                'label' => 'Días(P)',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'9',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de días planificados para la acción de control',
                    'onblur'=>'validateFields("5")',
                    'onkeyup'=>'validateFields("5")',
                    'style'=>'width:120px;')))
            ->add('diasReal', IntegerType::class, array(
                'label' => 'Días(R)',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'12',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de días reales usados para la acción de control',
                    'onblur'=>'validateFields("8")',
                    'onkeyup'=>'validateFields("8")',
                    'style'=>'width:120px;')))
            ->add('auditorXDiaPlan', IntegerType::class, array(
                'label' => 'Auditor/días(P)',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'10',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de auditores por día planificados para la acción de control',
                    'onblur'=>'validateFields("6")',
                    'onkeyup'=>'validateFields("6")',
                    'style'=>'width:120px;')))
            ->add('auditorXDiaReal', IntegerType::class, array(
                'label' => 'Auditor/días(R)',
                'required'=>false,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'13',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Cantidad de auditores por día reales que intervinieron en la acción de control',
                    'onblur'=>'validateFields("9")',
                    'onkeyup'=>'validateFields("9")',
                    'style'=>'width:120px;')))
            ->add('fechaInicioPlan', DateType::class,
                array('label'=>'F. inicio plan',
                    'attr'=>array(
                        'tabindex'=>'14',
                        'style'=>'width:190px;',
                        'title'=>'Fecha de inicio planificada de la acción de control',
                        'onblur'=>'llenarFinPlan(),validateFields("10")',
                        'onkeyup'=>'validateFields("10")',
                        'onchange'=>'validateFecha("1")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('fechaFinPlan', DateType::class,
                array('label'=>'F. fin plan',
                    'attr'=>array(
                        'tabindex'=>'15',
                        'title'=>'Fecha final planificada de la acción de control',
                        'onblur'=>'validateFields("11")',
                        'onkeyup'=>'validateFields("11")',
                        'onchange'=>'validateFecha("1")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('fechaInicioReal', DateType::class,
                array('label'=>'F. inicio real',
                    'attr'=>array(
                        'tabindex'=>'16',
                        'title'=>'Fecha de inicio real de la acción de control',
                        'onblur'=>'llenarFinReal(),validateFields("12")',
                        'onkeyup'=>'validateFields("12")',
                        'onchange'=>'validateFecha("2")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('fechaFinReal', DateType::class,
                array('label'=>'F. final real',
                    'attr'=>array(
                        'tabindex'=>'17',
                        'title'=>'Fecha final real de la acción de control',
                        'onblur'=>'validateFields("13")',
                        'onkeyup'=>'validateFields("13")',
                        'onchange'=>'validateFecha("2")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('calificacion', TextType::class, array(
                'label'=>'Calificación',
                'required'=>false,
                'attr'=>array(
                    'tabindex'=>'3',
                    'title'=>'Calificación de cada acción de control',
                    'minlength'=>'1',
                    'maxlength'=>'10',
                    'pattern'=>'[A-Za-z\s\-]{1,10}',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'style'=>'width: 200px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre calificación')))
            ->add('danoCUP', MoneyType::class, array(
                'label' => 'CUP',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'18',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("15")',
                    'onkeyup'=>'validateFields("15")',
                    'title'=>'Daño económico en CUP',
                    'style'=>'width:180px;')))
            ->add('danoCUC', MoneyType::class, array(
                'label' => 'CUC',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'19',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("16")',
                    'onkeyup'=>'validateFields("16")',
                    'title'=>'Daño económico en CUC',
                    'style'=>'width:180px;')))
            ->add('danoOtraMoneda', MoneyType::class, array(
                'label' => 'Otra mon.',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'20',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("17")',
                    'onkeyup'=>'validateFields("17")',
                    'title'=>'Daño económico en otras monedas',
                    'style'=>'width:180px;')))
            ->add('planMedidas', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'4',
                    'style'=>'margin-top: 15px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'Control del P.M.',
                'label_attr'=>array('style'=>'cursor: pointer;')))
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
                    'tabindex'=>'5',
                    'style'=>'width:400px',
                    'title'=>'Todas las entidades del sistema',
                    'class'=>'form-control')
            ))
            ->add('tipoAccion', EntityType::class, array(
                'label' => 'Tipo de acción',
                'class' => 'App\Entity\TipoAccion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.activo=1')
                        ->orderBy('t.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'6',
                    'style'=>'width:200px',
                    'title'=>'Todos los tipos de acción que se pueden realizar',
                    'class'=>'form-control')
            ))
            ->add('particularidad', EntityType::class, array(
                'class' => 'App\Entity\Particularidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.activo=1')
                        ->orderBy('p.nombre', 'ASC');
                },
                'choice_label' => 'siglas',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'7',
                    'style'=>'width:200px',
                    'title'=>'Todas las particularidades que se pueden presentar',
                    'class'=>'form-control')
            ))
            ->add('combustible', CombustibleType::class, array(
                'by_reference' => false,
                /*'block_name' => 'implicados_lists',*/
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccionControl::class,
        ]);
    }
}
