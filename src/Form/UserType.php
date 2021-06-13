<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'attr'=>array(
                    'class'=>'w3-input user-input',
                    'tabindex'=>'2',
                    'onblur'=>'validateEmail()',
                    'maxlength'=>'50',
                    'style'=>'width: 450px;background-color:transparent;',
                    'placeholder'=>'Enter email'),
                'label_attr'=>array('class'=>'w3-label w3-validate'),
                #'error_bubbling' => true,
                ))
            ->add('username', TextType::class, array(
                'attr'=>array(
                    'class'=>'w3-input user-input',
                    'tabindex'=>'1',
                    'minlength'=>'4',
                    'maxlength'=>'90',
                    'onblur'=>'validateUsername()',
                    'onkeyup'=>'validateUsername();validatePasswordForUsername()',
                    'style'=>'width: 450px;background-color:transparent;',
                    'placeholder'=>'Enter username'),
                'label'=>'Usuario',
                'label_attr'=>array('class'=>'w3-label w3-validate'),

                #'error_bubbling' => true,
                ))
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
                        return ['checked'=>'checked', 'style' => 'cursor:pointer;','class' => 'my-checkbox','tabindex'=>'3'];
                    if ($val=='ROLE_SUPER_ADMIN')
                        return ['style' => 'cursor:pointer; margin-right:5px;','tabindex'=>'3'/*, 'disabled'=>'disabled'*/];
                    return ['class' => 'my-checkbox disabled', 'style' => 'cursor:pointer;','tabindex'=>'3'];
                },
                'expanded' => true,
                'multiple' => true,
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Contraseña',
                    'attr'=>array(
                        'class'=>'w3-input user-input',
                        'tabindex'=>'4',
                        'minlength'=>'5',
                        'maxlength'=>'20',
                        'onblur'=>'validatePassword1()',
                        'onkeyup'=>'validatePassword1();validatePassword2()',
                        'style'=>'width: 450px;background-color:transparent;',
                        'placeholder'=>'Enter password'),
                    'label_attr'=>array('class'=>'w3-label w3-validate'),
                ),
                'second_options' => array(
                    'label' => 'Repetir Contraseña',
                    'attr'=>array(
                        'class'=>'w3-input user-input',
                        'tabindex'=>'5',
                        'onblur'=>'validatePassword2()',
                        'onkeyup'=>'validatePassword2()',
                        'style'=>'width: 450px;background-color:transparent;',
                        'placeholder'=>'Confirm password'),
                    'label_attr'=>array('class'=>'w3-label w3-validate'),
                )
            ))
            ->add('isActive',CheckboxType::class, array(
                'attr'=>array(
                    'style'=>'margin-left: 5px; cursor: pointer;'),
                'label' => 'Activo',
                'label_attr'=>array('style'=>'margin-top: 10px; cursor: pointer;'),
                'required' => false,
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
