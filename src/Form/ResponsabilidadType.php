<?php

namespace App\Form;

use App\Entity\Implicado;
use App\Entity\MedidaDisciplinaria;
use App\Entity\Responsabilidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResponsabilidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('implicado', EntityType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'padding: 0; margin-right:395px;',
                ),
                'class' => 'App\Entity\Implicado',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->where('i.activo=1')
                        ->orderBy('i.nombre', 'ASC');
                },
                'group_by' => function($choice, $key, $value) {
                    /**
                     * @var Implicado $choice
                     */
                    if ($choice->getHC()!=null) {
                        if ($choice->getResponsabilidad()!=null) {
                            if ($choice->getHC() === $choice->getResponsabilidad()->getHC()) {
                                return 'Este H.C.';
                            }
                            else {
                                return 'Otro H.C.';
                            }
                        }
                        else {
                            return 'Sin medida';
                        }
                    } else {
                        return 'P.H.D.';
                    }
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array('class'=>'form-control float-right', 'style'=>'width: 450px; margin-top: 28px; margin-right:-450px;', 'tabindex'=>'2')
            ))
            ->add('medidaDisciplinaria', EntityType::class, array(
                'class' => 'App\Entity\MedidaDisciplinaria',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.activo=1')
                        ->orderBy('m.nombre', 'ASC');
                },
                'group_by' => function($choice, $key, $value) {
                    /**
                     * @var MedidaDisciplinaria $choice
                     */
                    if ($choice->getCategoria() == 'Trabajadores') {
                        return 'Trabajadores';
                    } else if ($choice->getCategoria() == 'Ejecutivos'){
                        return 'Ejecutivos';
                    } else if ($choice->getCategoria() == 'Directivos'){
                        return 'Directivos';
                    } else {
                        return 'Directivos Superiores';
                    }
                },
                'choice_label' => 'nombre',
                /*'placeholder' => 'Todas'*/
                'attr'=>array('class'=>'form-control', 'style'=>'width: 400px;', 'tabindex'=>'1')
            ))
            ->add('medidasPendientes', IntegerType::class, array(
                'label_attr'=>array(
                    'class'=>'float-right',
                    'style'=>'padding: 0; margin-right:550px; margin-top:20px;',
                ),
                'label' => 'Medidas pendientes',
                'required' => false,
                'attr'=>array(
                    'class'=>'form-control float-right',
                    'tabindex'=>'4',
                    'min'=>'1',
                    'max'=>'99',
                    'pattern'=>'[0-9\s\-\/\.]{1,2}',
                    'title'=>'Medidas pendientes',
                    'onblur'=>'validateFields("2")',
                    'onkeyup'=>'validateFields("2")',
                    'style'=>'width:170px; margin-top: 48px; margin-right:-165px;')))
            ->add('medidasTotal', IntegerType::class, array(
                'label_attr'=>array(
                    'style'=>'margin-top:20px;',
                ),
                'required' => false,
                'label' => 'Total de medidas',
                'attr'=>array(
                    'class'=>'form-control',
                    'tabindex'=>'3',
                    'min'=>'1',
                    'max'=>'99',
                    'pattern'=>'[0-9\s\-\/\.]{1,2}',
                    'title'=>'Total de medidas',
                    'onblur'=>'validateFields("1")',
                    'onkeyup'=>'validateFields("1")',
                    'style'=>'width:170px;')))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Responsabilidad::class,
        ]);
    }
}
