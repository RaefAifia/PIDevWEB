<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\LieuEvenement;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('prix')
            ->add('domaine')
            ->add('capacite')
            ->add('imageFile', FileType::class,
                array(
                    'required'=>false,

                    'attr' => array(
                        'accept' => "image/jpeg, image/png"
                    ),
                    'constraints' => [
                        new File([
                            'maxSize' => '2M',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a JPG or PNG',
                        ])
                    ]
                ))
            ->add('titre')
            ->add('description')
            ->add('date_evenement',DateType::class,[
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])

            ->add('lieu_id',EntityType::class, [
                // looks for choices from this entity
                'class' => LieuEvenement::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'titre',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
