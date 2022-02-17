<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon email'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon email'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => "Mon mot de passe",
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'mon mot de passe actuel'
                ] 
            ])    
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => "le mot de passe de confirmation n'est pas le même", 
                'label' => "Mon nouveau mot de passe",
                'required' => true,
                'first_options' => [
                    'label' => 'Mon nouveau mot de Passe',
                    'attr'  => [
                        'placeholder' => '* * * * * *'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez le nouveau mot de passe',
                    'attr'  => [
                        'placeholder' => '* * * * * *'
                    ]
                ]
            ] )
            ->add('submit', SubmitType::class, [
                'label' => "Mettre à jour"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
