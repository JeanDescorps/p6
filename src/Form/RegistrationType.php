<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Nom d\'utilisateur',
                'attr' => [
                    'placeholder' => 'Nom d\'utilisateur',
                ],
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ))
            ->add('password', PasswordType::class, array(
                'label' => 'Mot de passe',
                'attr' => [
                    'placeholder' => 'Mot de passe',
                ],
            ))
            ->add('passwordConfirm', PasswordType::class, array(
                'label' => 'Confirmation de mot de passe',
                'attr' => [
                    'placeholder' => 'Veuillez confirmer votre mot de passe',
                ],
            ))
            ->add('Inscription', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-success',
                ],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
