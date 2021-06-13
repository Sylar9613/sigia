<?php

namespace App\Form;

use App\Entity\Osde;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OsdeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array(
            'attr'=>array(
                'class'=>'form-control',
                'minlength'=>'3',
                'maxlength'=>'70',
                'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,Ú0-9A-Za-zñÑ\s\-\.]{3,70}',
                'title'=>'Nombre de cada O.S.D.E. del sistema',
                'onblur'=>'validateFields()',
                'onkeyup'=>'validateFields()',
                'style'=>'width: 450px;',
                'tabindex'=>'1',
                'placeholder'=>'Enter OSDE')))
            ->add('organismo', EntityType::class, array(
                'class' => 'App\Entity\Organismo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->where('o.activo=1')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array(
                    'class'=>'form-control',
                    'style'=>'width: 450px;',
                    'tabindex'=>'2')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Osde::class,
        ]);
    }
}
