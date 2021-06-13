<?php

namespace App\Form;

use App\Entity\Phd;
use Doctrine\ORM\EntityRepository;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('unidadOrganizativa', TextType::class, array(
                'attr'=>array(
                    'tabindex'=>'1',
                    'title'=>'Unidad Organizativa a la que pertenece la entidad donde ocurrió el PHD',
                    'minlength'=>'2',
                    'maxlength'=>'50',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{2,50}',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'style'=>'width: 350px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre Unidad Organizativa')))
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
                    'tabindex'=>'2',
                    'style'=>'width:200px',
                    'title'=>'Todas las acciones de control insertadas',
                    'class'=>'form-control')
            ))
            ->add('fecha', DateType::class,
                array('label'=>'Fecha del PHD',
                    'attr'=>array(
                        'tabindex'=>'3',
                        'title'=>'Fecha de ocurrencia del PHD',
                        'onblur'=>'validateFields("3")',
                        'onkeyup'=>'validateFields("3")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                ))
            ->add('numeroExpediente', IntegerType::class, array(
                'label' => 'Número de Expediente',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'4',
                    'min'=>'1',
                    'max'=>'1000',
                    'pattern'=>'[0-9\s\-\/\.]{1,7}',
                    'title'=>'Número de Expediente',
                    'onblur'=>'validateFields("4")',
                    'onkeyup'=>'validateFields("4")',
                    'style'=>'width:200px;')))
            ->add('numeroCausa', IntegerType::class, array(
                'label' => 'Número de Causa',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'5',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9\s\-\/\.]{1,7}',
                    'title'=>'Número de Causa',
                    'onblur'=>'validateFields("5")',
                    'onkeyup'=>'validateFields("5")',
                    'style'=>'width:200px;')))
            ->add('danoEconomicoCup', MoneyType::class, array(
                'label' => 'Daños CUP',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'6',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("7")',
                    'onkeyup'=>'validateFields("7")',
                    'title'=>'Daño económico en CUP',
                    'style'=>'width:180px;')))
            ->add('danoEconomicoOtraMoneda', MoneyType::class, array(
                'label' => 'Daños Otras mon.',
                'required' => false,
                'currency' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'7',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("8")',
                    'onkeyup'=>'validateFields("8")',
                    'title'=>'Daño económico en otras monedas',
                    'style'=>'width:180px;')))
            ->add('sintesis', TextareaType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'12',
                    'cols'=>'45',
                    'rows'=>'4',
                    'title'=>'Síntesis del PHD',
                    'minlength'=>'1',
                    'maxlength'=>'65525',
                    'onblur'=>'validateFields("6")',
                    'aria-required'=>'true',
                    'placeholder'=>'Entre la síntesis'),
                'label'=>'Síntesis'))
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
                    'tabindex'=>'8',
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
                    'tabindex'=>'11',
                    'style'=>'width:200px',
                    'title'=>'Todos los tipos de acción que se pueden realizar',
                    'class'=>'form-control')
            ))
            ->add('situacion', EntityType::class, array(
                'label' => 'Situación',
                'class' => 'App\Entity\Situacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->where('s.activo=1')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'9',
                    'style'=>'width:400px',
                    'title'=>'Todas las situaciones que pueden ocurrir',
                    'class'=>'form-control')
            ))
            ->add('causaCondicion', EntityType::class, array(
                'label' => 'Causa/Condición',
                'class' => 'App\Entity\CausaCondicion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.activo=1')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'10',
                    'style'=>'width:200px',
                    'title'=>'Causas por las cuales puede ocurrir un PHD',
                    'class'=>'form-control')
            ));
            $builder->add('implicados', CollectionType::class, array(
                'required' => false,
                'entry_type' => ImplicadoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                /*'block_name' => 'implicados_lists',*/
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phd::class,
        ]);
    }
}
