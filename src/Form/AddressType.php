<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Notifier\Texter;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'quel nom souhaiteriez vous donner à votre adresse ?',
                'attr'  => [
                    'placeholder' => 'nommez votre adresse'
                ]
            ])
            ->add('firstname',TextType::class, [
                'label' => 'Quel est votre prénom ?',
                'attr'  => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Quel est votre nom ?',
                'attr'  => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company',TextType::class, [
                'label' => 'Votre société ?',
                'attr'  => [
                    'placeholder' => '(facultatif) le nom de votre société'
                ]
            ])
            ->add('address',TextType::class, [
                'label' => 'Votre adress ?',
                'attr'  => [
                    'placeholder' => '1 place pie'
                ]
            ])
            ->add('postal',TextType::class, [
                'label' => 'Code postal',
                'attr'  => [
                    'placeholder' => '75000'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'le nom de votre ville',
                'attr'  => [
                    'placeholder' => ' Entrez la ville'
                ]
            ])
            ->add('country',CountryType::class, [
                'label' => 'Pays',
                'attr'  => [
                    'placeholder' => 'le nom de votre pays'
                ]
            ])
            ->add('phone',TextType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr'  => [
                    'placeholder' => 'Entrez votre téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr'  => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
