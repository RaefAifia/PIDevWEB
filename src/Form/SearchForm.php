<?php


namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('q', TextType::class,[

        'label' => false,
        'required' => false,
        'attr' => [
            'placeholder' => 'Rechercher']
    ])

            ->add('domaine',ChoiceType::class, [
                'choices' => [
                    'danse' => 'danse',
                    'theatre' => 'theatre',
                    'musique' => 'musique',
                    'littérature' => 'littérature',
                    'audiovisuel' => 'audiovisuel',
                    'peinture' => 'peinture',
                    'sculpture' => 'sculpture',
                ]])

            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])


            ->add('niveau',ChoiceType::class, [
                'choices' => [
                    'Débutant' => 'Débutant',
                    'intermédiare' => 'intermédiaire',
                    'avancé' => 'avancé',
                ]
            ]);




    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}