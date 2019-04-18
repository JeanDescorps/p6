<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use App\Form\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre du trick',
                ],
            ])
            ->add('lead', TextType::class, [
                'label' => 'Chapô',
                'attr' => [
                    'placeholder' => 'Présenter le trick en une phrase',
                ],
            ])
            ->add('file', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'placeholder' => 'Ajouter ou modifier l\'image principale du trick',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'label' => 'Catégorie',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description du trick',
                ],
            ])
            ->add('images', CollectionType::class, [
                'required' => false,
                'entry_type' => ImageType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('videos', CollectionType::class, [
                'required' => false,
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
