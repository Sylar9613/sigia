<?php

namespace App\Form;

use App\Entity\Auditor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AuditorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activo', CheckboxType::class, array(
            'attr'=>array(
                'tabindex'=>'1',
                'style'=>'margin-top: 20px; margin-left: 5px; cursor: pointer;'),
            'required' => false,
            'label_attr'=>array('style'=>'margin-top: 20px; cursor: pointer;')))
            ->add('imagen', FileType::class, [
                'attr'=>array(
                    'class'=>'position-static',
                    'onchange'=>'mostrarPath();'),
                'label' => 'Imagen (JPG o PNG file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // everytime you edit the Product details
                'required' => false,
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            /*'application/pdf',
                            'application/x-pdf',*/
                        ],
                        'mimeTypesMessage' => 'Por favor, suba una foto del auditor.',
                    ])
                ],
            ])
            /*->add('imagen', FileType::class, array(
                'attr'=>array('class'=>'position-static','onchange'=>'alert("nose");'),
                'label' => 'Imagen (JPG file)'))*/
            ->add('nombres', TextType::class, array(
                'attr'=>array(
                    'tabindex'=>'3',
                    'title'=>'Nombres de cada auditor del sistema',
                    'minlength'=>'2',
                    'maxlength'=>'60',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{2,60}',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'style'=>'width: 450px;',
                    'class'=>'form-control',
                    'placeholder'=>'Enter nombres')))
            ->add('apellidos', TextType::class, array(
                'attr'=>array(
                    'tabindex'=>'4',
                    'title'=>'Apellidos de cada auditor del sistema',
                    'style'=>'width: 450px;',
                    'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{2,60}',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'60',
                    'placeholder'=>'Enter apellidos')))
            ->add('ci', TextType::class, array(
                'attr'=>array(
                    'tabindex'=>'5',
                    'title'=>'Carnet de identidad de cada auditor del sistema',
                    'minlength'=>'11',
                    'maxlength'=>'11',
                    'pattern'=>'[0-9]{11}',
                    'onblur'=>'validateFields("3")',
                    'onkeyup'=>'validateFields("3")',
                    'style'=>'width: 450px;',
                    'class'=>'form-control', 'placeholder'=>'Enter CI'),
                'label'=>'Carnet de Identidad'))
            ->add('localidad', EntityType::class, array(
                'class' => 'App\Entity\Localidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->where('l.activo=1')
                        ->orderBy('l.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'10',
                    'title'=>'Todas las localidades a las que pertenecen los auditores del sistema',
                    'class'=>'form-control')
            ))
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
                    'style'=>'width: 450px;',
                    'tabindex'=>'7',
                    'title'=>'Todas las entidades que pueden ser asignadas a los auditores del sistema',
                    'class'=>'form-control')
            ))
            ->add('cargo', EntityType::class, array(
                'class' => 'App\Entity\Cargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.activo=1')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'tabindex'=>'8',
                    'title'=>'Todos los cargos que pueden ser asignados a los auditores del sistema',
                    'class'=>'form-control')
            ))
            ->add('nivel', EntityType::class, array(
                'class' => 'App\Entity\Nivel',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('n')
                        ->where('n.activo=1')
                        ->orderBy('n.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'style'=>'width: 450px;',
                    'tabindex'=>'9',
                    'title'=>'Todos los niveles escolares que pueden poseer los auditores del sistema',
                    'class'=>'form-control')
            ))
            ->add('direccion', TextareaType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'11',
                    'cols'=>'45',
                    'rows'=>'3',
                    'title'=>'Dirección de cada auditor del sistema',
                    'minlength'=>'1',
                    'maxlength'=>'65525',
                    'onblur'=>'validateFields("8")',
                    'aria-required'=>'true',
                    'placeholder'=>'Enter Dirección'),
                'label'=>'Dirección'))
            ->add('telefono', TelType::class, array(
                'attr'=>array(
                    'style'=>'width: 230px;',
                    'tabindex'=>'15',
                    'title'=>'Teléfono (si posee) de cada auditor del sistema',
                    'minlength'=>'3',
                    'maxlength'=>'20',
                    'pattern'=>'[ú,0-9A-Za-z\s\/\+]{3,20}',
                    'onblur'=>'validateFields("7")',
                    'onkeyup'=>'validateFields("7")',
                    'class'=>'form-control', 'placeholder'=>'Teléfono'),
                'label'=>'Teléfono'))
            ->add('correo', EmailType::class, array(
                'attr'=>array(
                    'class'=>'form-control',
                    'title'=>'Correo de cada auditor del sistema',
                    'onblur'=>'validateFields("4")',
                    'onkeyup'=>'validateFields("4")',
                    'tabindex'=>'6',
                    'maxlength'=>'30',
                    'placeholder'=>'Enter email')))
            ->add('fea', CheckboxType::class, array(
                'attr'=>array(
                    'tabindex'=>'14',
                    'title'=>'Verificar si el auditor proviene de la Formación Emergente de Auditores (F.E.A.)',
                    'style'=>'margin-top: 20px; margin-left: 5px; cursor: pointer;'),
                'required' => false,
                'label'=>'F.E.A.', 'label_attr'=>array('style'=>'margin-top: 20px; cursor: pointer;')))
            ->add('rna', TextType::class, array(
                'attr'=>array(
                    'style'=>'width: 250px;',
                    'class'=>'form-control',
                    'title'=>'Número de cada auditor del sistema en el Registro Nacional de Auditores y Contralores',
                    'tabindex'=>'12',
                    'minlength'=>'1',
                    'maxlength'=>'10',
                    'pattern'=>'[0-9]{1,10}',
                    'onblur'=>'validateFields("5")',
                    'onkeyup'=>'validateFields("5")',
                    'placeholder'=>'Enter R.N.A.'),
                'label'=>'Registro Nacional de Auditores (R.N.A.)'))
            ->add('fechaRna', DateType::class,
                array('label'=>'Fecha inscripción en el R.N.A.',
                    'attr'=>array(
                        'tabindex'=>'13',
                        'title'=>'Fecha en que fue inscrito cada auditor del sistema en el Registro Nacional de Auditores y Contralores',
                        'onblur'=>'validateFields("6")',
                        'onkeyup'=>'validateFields("6")',
                        'class'=>'form-control'),
                    /*'data' => new \DateTime('now'),*/
                    'widget' => 'single_text',
                    /*'html5' => false,*/
                    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auditor::class,
        ]);
    }
}
