<?php

namespace App\Form;

use App\Entity\Cargo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class, array(
            'attr'=>array(
                'class'=>'form-control col-6',
                'minlength'=>'3',
                'maxlength'=>'100',
                'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-\.]{3,100}',
                'title'=>'Nombre de cada cargo que pueden poseer los auditores',
                'onblur'=>'validateFields()',
                'onkeyup'=>'validateFields()',
                'tabindex'=>'1',
                'placeholder'=>'Escriba el cargo')))
            ->add('esContralor', CheckboxType::class,
                array(
                    'attr'=>array(
                        'tabindex'=>'2',
                        'style'=>'margin-top: 20px; margin-left: 5px; cursor: pointer;'),
                    'required' => false,
                    'label'=>'Contralor',
                    'label_attr'=>array('style'=>'cursor: pointer;')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cargo::class,
        ]);
    }
}
