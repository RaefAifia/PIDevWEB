<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('date')
            ->add('duree')
            ->add('lieu')
            ->add('prix')
            ->add('niveau',ChoiceType::class, [
                'choices' => [
                    'Débutant' => 'Débutant',
                    'intermédiare' => 'intermédiaire',
                    'avancé' => 'avancé',
                ]])

            ->add('langue')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false])
         /*   ->add('isvalid')*/
            ->add('titre')
           /* ->add('user')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
