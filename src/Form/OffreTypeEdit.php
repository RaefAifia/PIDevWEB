<?php


namespace App\Form;

use App\Entity\Offre;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType ;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class OffreTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('description' , CKEditorType::class, [
                'attr' => ['class' => 'form-control']])
            ->add('date', DateTimeType::class,
                )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}