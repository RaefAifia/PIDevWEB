<?php

namespace App\Form;

use App\Entity\Offre;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description' , CKEditorType::class, [
                'attr' => ['class' => 'form-control']])
            ->add('nbClient')
            ->add('date', DateTimeType::class,[
                'input' => 'datetime_immutable'
                ]
                )
            ->add('x', ChoiceType::class, [
                'choices'  => [
                    'fidèles clients' => "fidèles clients",
                    'nouveaux utilisateurs' => "nouveaux utilisateurs",
                    'anciens utilisateurs' => "anciens utilisateurs",

                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
