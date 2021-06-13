<?php

namespace App\Form;

use App\Entity\HC;
use App\Entity\Phc;
use App\Repository\PhcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HCType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $er = new Phc();
        $builder
            ->add('numeroExpediente', IntegerType::class, array(
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
                    'style'=>'width:180px;')))
            ->add('resumen', TextareaType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'14',
                    'cols'=>'40',
                    'rows'=>'6',
                    'title'=>'Resumen del H.C.',
                    'minlength'=>'1',
                    'maxlength'=>'65525',
                    'onblur'=>'validateFields("14")',
                    'aria-required'=>'true',
                    'placeholder'=>'Escriba el resumen')
            ))
            ->add('objetoSocialEntidad', TextareaType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'13',
                    'cols'=>'30',
                    'rows'=>'2',
                    'title'=>'Objeto social de la entidad',
                    'minlength'=>'1',
                    'maxlength'=>'65525',
                    'onblur'=>'validateFields("13")',
                    'aria-required'=>'true',
                    'placeholder'=>'Entre el objeto social'),
                'label'=>'Objeto social'))
            ->add('totalImplicadosEntidad', IntegerType::class, array(
                'label' => 'T. Imp. entidad',
                'required' => false,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'9',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Total de implicados de la entidad',
                    'onblur'=>'validateFields("9")',
                    'onkeyup'=>'validateFields("9")',
                    'style'=>'width:180px;')))
            ->add('totalImplicadosOtras', IntegerType::class, array(
                'label' => 'T. Imp. otras',
                'required' => false,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'10',
                    'min'=>'1',
                    'max'=>'100',
                    'pattern'=>'[0-9]{1,3}',
                    'title'=>'Total de implicados de otras entidades',
                    'onblur'=>'validateFields("10")',
                    'onkeyup'=>'validateFields("10")',
                    'style'=>'width:180px;')))
            ->add('afectacionEconomicaCUP', MoneyType::class, array(
                'label' => 'Afect. CUP',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'tabindex'=>'11',
                    'onblur'=>'validateFields("7")',
                    'onkeyup'=>'validateFields("7")',
                    'title'=>'Afectación económica en CUP',
                    'style'=>'width:180px;')))
            ->add('recuperadoCUP', MoneyType::class, array(
                'label' => 'Recuperado CUP',
                'currency' => false,
                'required' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'12',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("8")',
                    'onkeyup'=>'validateFields("8")',
                    'title'=>'Recuperado en CUP',
                    'style'=>'width:180px;')))
            ->add('phc', EntityType::class, array(
                'label' => 'Número Exp. PHC',
                'class' => 'App\Entity\Phc',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.activo=1')/*
                        ->orderBy('p.numero', 'ASC')*/;
                },
                /*'group_by' => function($choice, $key, $value) {
                    /**
                     * @var Phc $choice
                     */
                    /*if ($choice->getHC()!=null) {
                        return 'Posee H.C.';
                    } else {
                        return 'No posee H.C.';
                    }
                },*/
                'choice_attr' => function($choice, $key, $value) {
                    /**
                     * @var Phc $choice
                     */
                    if ($choice->getHC()!=null) {
                        return ['class' => 'collapse'];
                    }
                    return ['class' => 'visible'];
                },
                'choice_label' => 'numero',
                'placeholder' => 'P.H.C.',
                /*'required' => false,*/
                'attr'=>array(
                    'tabindex'=>'9',
                    'style'=>'width:200px',
                    'title'=>'Todos los P.H.C. que no poseen hechos de corrupción asociados',
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
        $builder->add('responsabilidad', CollectionType::class, array(
            'required' => false,
            'entry_type' => ResponsabilidadType::class,
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
            'data_class' => HC::class,
        ]);
    }
}

    /*->add('categoria', TextType::class, array(
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
            'style'=>'width: 200px;',
            'class'=>'form-control',
            'value'=>'Matanzas',
            'placeholder'=>'Matanzas')))
    ->add('fuente', TextType::class, array(
        'label'=>'Fuente o vía de detección',
        'attr'=>array(
            'tabindex'=>'4',
            'title'=>'Fuente o vía de detección del H.C.',
            'minlength'=>'3',
            'maxlength'=>'50',
            'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,50}',
            'onblur'=>'validateFields("4")',
            'onkeyup'=>'validateFields("4")',
            'style'=>'width: 400px;',
            'class'=>'form-control',
            'placeholder'=>'Entre fuente de detección')))
    ->add('fechaDeteccion', DateType::class,
        array('label'=>'Fecha de detección del H.C.',
            'attr'=>array(
                'tabindex'=>'5',
                'title'=>'Fecha de detección del H.C.',
                'onblur'=>'validateFields("5")',
                'onkeyup'=>'validateFields("5")',
                'class'=>'form-control'),
            'widget' => 'single_text',
        ))
    ->add('fechaOcurrencia', DateType::class,
        array('label'=>'Fecha de ocurrencia del H.C.',
            'attr'=>array(
                'tabindex'=>'6',
                'title'=>'Fecha de ocurrencia del H.C.',
                'onblur'=>'validateFields("6")',
                'onkeyup'=>'validateFields("6")',
                'class'=>'form-control'),
            'widget' => 'single_text',

        ))*//*
    ->add('entidad', EntityType::class, array(
    'class' => 'App\Entity\Entidad',
    'query_builder' => function (EntityRepository $er) {
        return $er->createQueryBuilder('e')
            ->where('e.activo=1')
            ->orderBy('e.nombre', 'ASC');
    },
    'choice_label' => 'nombre',
    'attr'=>array(
        'tabindex'=>'7',
        'style'=>'width:400px',
        'title'=>'Todas las entidades del sistema',
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
        'attr'=>array(
            'tabindex'=>'8',
            'style'=>'width:240px',
            'title'=>'Todos los municipios del sistema',
            'class'=>'form-control')
    ))*/