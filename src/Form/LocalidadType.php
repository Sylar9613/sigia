<?php

namespace App\Form;

use App\Entity\Localidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array(
            'attr'=>array(
                'class'=>'form-control',
                'minlength'=>'3',
                'maxlength'=>'50',
                'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,50}',
                'title'=>'Nombre de cada localidad del sistema',
                'onblur'=>'validateFields()',
                'onkeyup'=>'validateFields()',
                'style'=>'width: 450px;',
                'tabindex'=>'1',
                'placeholder'=>'Enter localidad')))
            ->add('municipio', EntityType::class, array(
                'class' => 'App\Entity\Municipio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.activo=1')
                        ->orderBy('m.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array('class'=>'form-control', 'style'=>'width: 450px;', 'tabindex'=>'2')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Localidad::class,
        ]);
    }
}
