<?php

namespace App\Form;

use App\Entity\Oeuvrage;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Vich\UploaderBundle\Form\Type\VichImageType;

class OeuvrageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')

            ->add('domaine', ChoiceType::class, [
                'choices' => [
                    'Peinture' => 'Peinture',
                    'Artisanat' => 'Artisanat',
                   'Décoration'  => 'Décoration'
                    ,'Sculpture' => 'Sculpture'
                    ,'Litérature' => 'Litérature'
                ],'required' => false ,

            ] )
            ->add('prix')

            ->add('quantite')
            ->add('description', CKEditorType::class)

        ->add('image', FileType::class, [

                    'mapped' => false,

                    'required' => false,

                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                "image/png", "image/jpeg", "image/jpg"
                            ],
                            'mimeTypesMessage' => 'le format de l image nest pas accepté',

                        ])
                    ],
                'label'=>'inserer une image',
                'data_class' => null
                ]
            )

         ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Oeuvrage::class,
        ]);
    }
}
