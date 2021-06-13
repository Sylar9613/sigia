<?php

namespace App\Form;

use App\Entity\Municipio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MunicipioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', TextType::class,
            array('attr'=>array(
                'class'=>'form-control',
                'minlength'=>'3',
                'maxlength'=>'40',
                'pattern'=>'[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ\s\-]{3,40}',
                'title'=>'Nombre de cada municipio del sistema',
                'onblur'=>'validateFields()',
                'onkeyup'=>'validateFields()',
                'placeholder'=>'Enter municipio')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Municipio::class,
        ]);
    }
}
