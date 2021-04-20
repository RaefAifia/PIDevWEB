<?php

namespace App\Form;

use App\Entity\LieuEvenement;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuEvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('longitude',NumberType::class, [

                'required' => true,
                'attr' => [
                    'int' => true,
                ]
            ])
            ->add('latitude',NumberType::class, [

                'required' => true,
                'attr' => [
                    'int' => true,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LieuEvenement::class,
        ]);
    }
}
