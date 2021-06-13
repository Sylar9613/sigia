<?php
/**
 * Created by PhpStorm.
 * User: Arian-PC
 * Date: 04/04/2020
 * Time: 21:02 PM
 */

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeRolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'SUPER ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ADMIN' => 'ROLE_ADMIN',
                    'SUPERVISOR' => 'ROLE_SUPERVISOR',
                    'ATENCIÓN POBLACIÓN' => 'ROLE_ATP',
                    'JURÍDICO' => 'ROLE_JURIDICO',
                    'USUARIO' => 'ROLE_USER'
                ),
                // *this line is important*
                /*'choices_as_values' => true,*/
                'choice_attr'=>function($val, $key, $index) {
                    if ($val=='ROLE_USER')
                        return ['checked'=>'checked', 'class' => 'my-checkbox','tabindex'=>'3'];
                    if ($val=='ROLE_SUPER_ADMIN')
                        return ['style' => 'cursor:pointer; margin-right:5px;','tabindex'=>'3'/*, 'disabled'=>'disabled'*/];
                    return ['class' => 'my-checkbox disabled','tabindex'=>'3'];
                },
                'expanded' => true,
                'multiple' => true,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}