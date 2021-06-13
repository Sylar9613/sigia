<?php

namespace App\Form;

use App\Entity\Plaza;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlazaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plazas', null, array(
            'attr'=>array(
                'class'=>'form-control',
                'tabindex'=>'1',
                'min'=>'1',
                'max'=>'40',
                'title'=>'Cantidad de plazas para cada cargo',
                'onblur'=>'validatePlazas()',
                'onkeyup'=>'validatePlazas()',
                'style'=>'width:20%;')))
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
                    'class'=>'form-control col-5',
                    'title'=>'Todas las entidades del sistema',
                    'tabindex'=>'2')
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
                    'class'=>'form-control',
                    'title'=>'Todos los cargos del sistema',
                    'style'=>'width: 450px;',
                    'tabindex'=>'3')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plaza::class,
        ]);
    }
}
