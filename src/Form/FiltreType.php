<?php


namespace App\Form;


use App\Entity\Categorie;
use App\Entity\FavorisO;
use App\Entity\FiltreOeuvre;
use App\Entity\Oeuvrage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('domaine', ChoiceType::class, [
                'choices' => [
                    'Peinture' => 'Peinture',
                    'Artisanat' => 'Artisanat',
                    'Décoration'  => 'Décoration'
                    ,'Sculpture' => 'Sculpture'
                    ,'Litérature' => 'Litérature'
                ],
                'required' => false ,
                'label' => false,
            ] )

            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('min', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])
            ->add('max', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltreOeuvre::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);

}

}
