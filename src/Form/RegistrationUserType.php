<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Your email(will be username)'
    ])

            ->add('name', TextType::class,[
                'label' => 'Name'
    ])

            ->add('lastname', TextType::class,[
                'label' => 'Lastname'
            ])

            ->add('date', DateType::class,[
                'label' => 'Date birth'
            ])

            ->add('gender', TextType::class,[
                'label' => 'Gender (M or N)'
            ])

            ->add('phone', TextType::class,[
                'label' => 'Phone number'
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
