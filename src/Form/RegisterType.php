<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre Prénom',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 40
                ]),
                'attr' => [
                    'placeholder' => "Entrez votre prénom"
                ]])
            ->add('lastname', TextType::class, [
                'label' => "Votre Nom",
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 40
                ]),
                'attr' => [
                    'placeholder' => "Entrez votre Nom"
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr'  => [
                   'placeholder'=>'Entrez votre email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => "le mot de passe de confirmation n'est pas le même", 
                'label' => "Votre mot de passe",
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de Passe',
                    'attr'  => [
                        'placeholder' => '* * * * * *'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passe',
                    'attr'  => [
                        'placeholder' => '* * * * * *'
                    ]
                ]
            ] )
           /* ->add('password_confirm', PasswordType::class, [
                'label' => "Confirmez votre mot de passe",
                'mapped' => false,
                'attr'  => [
                    'placeholder' => '* * * * * *'
                ]
            ])*/
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
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
