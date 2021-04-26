<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Formation;
use App\Entity\Distributeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('niveau',ChoiceType::class, [
                'choices' => [
                    'Débutant' => 'Débutant',
                    'intermédiare' => 'intermédiaire',
                    'avancé' => 'avancé',
                ]])
            ->add('description')
            ->add('duree')
            ->add('image', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false])

            //->add('formation',EntityType::class, ['class' => Formation::class, 'choice_label'=> 'titre'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
