<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                'label' => 'Mot de passe actuel',
                'attr' => [
                    'placeholder' => 'Votre mot de passe actuel',
                ],
            ))
            ->add('newPassword', PasswordType::class, array(
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Votre nouveau mot de passe',
                ],
            ))
            ->add('confirmPassword', PasswordType::class, array(
                'label' => 'Confirmation de mot de passe',
                'attr' => [
                    'placeholder' => 'Veuillez confirmer votre mot de passe',
                ],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_field_name' => '_token',
        ]);
    }
}
