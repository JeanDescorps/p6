<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('username', TextType::class, array(
                'label' => 'Nom d\'utilisateur',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
            ))
            ->add('avatar', FileType::class, array(
                'label' => 'Avatar',
                'required' => false,
                'data_class' => null,
                'empty_data' => $user->getAvatar(),
            ))
            ->add('website', UrlType::class, array(
                'label' => 'Website',
                'required' => false,
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => User::class,
            'csrf_field_name' => '_token',
        ]);
    }
}
