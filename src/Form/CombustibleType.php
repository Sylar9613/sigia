<?php

namespace App\Form;

use App\Entity\Combustible;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CombustibleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('evaluacion', TextType::class, array(
                'label'=>'Evaluación',
                'attr'=>array(
                    'tabindex'=>'21',
                    'title'=>'Evaluación de la situación del combustible',
                    'minlength'=>'1',
                    'maxlength'=>'1',
                    'pattern'=>'[A|D|M,a|d|m]{1}',
                    'onblur'=>'validateFields("14")',
                    'onkeyup'=>'validateFields("14")',
                    'style'=>'width: 200px;',
                    'class'=>'form-control',
                    'placeholder'=>'Entre evaluación')))
            ->add('danoEconomicoCup', MoneyType::class, array(
                'label' => 'CUP',
                'required' => false,
                'currency' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'22',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("18")',
                    'onkeyup'=>'validateFields("18")',
                    'title'=>'Daño económico en CUP',
                    'style'=>'width:200px;')))
            ->add('danoEconomicoOtraMoneda', MoneyType::class, array(
                'label' => 'Otra moneda',
                'required' => false,
                'currency' => false,
                'grouping' => true,
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'23',
                    'pattern'=>'[0-9\x2C\x2E]{1,10}',
                    'onblur'=>'validateFields("19")',
                    'onkeyup'=>'validateFields("19")',
                    'title'=>'Daño económico en otra moneda',
                    'style'=>'width:200px;')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Combustible::class,
        ]);
    }
}
