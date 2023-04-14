<?php

namespace App\Form;

use App\Entity\Cow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight', null,[
                'label' => 'Peso em kg',
            ])
            ->add('milkAmount', null,[
                'label' => 'Quantidade de Leite produzido por semana',
            ])
            ->add('foodAmount', null,[
                'label' => 'Quantidade de Comida por semana',
            ])
            ->add('born', null,[
                'label' => 'Data de Nascimento',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cow::class,
        ]);
    }
}
