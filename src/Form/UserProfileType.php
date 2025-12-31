<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your first name'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your last name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your email address'
                ]
            ])
            ->add('age', NumberType::class, [
                'label' => 'Age',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your age',
                    'min' => 13,
                    'max' => 120
                ]
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Weight',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter weight in kg',
                    'step' => '0.1',
                    'min' => 30
                ]
            ])
            ->add('height', NumberType::class, [
                'label' => 'Height',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter height in cm',
                    'min' => 100,
                    'max' => 250
                ]
            ])
            ->add('goal', ChoiceType::class, [
                'label' => 'Fitness Goal',
                'choices' => [
                    'Weight Loss' => 'weight_loss',
                    'Muscle Gain' => 'muscle_gain',
                    'Maintenance' => 'maintenance',
                    'Improve Endurance' => 'endurance',
                    'General Fitness' => 'general_fitness',
                ],
                'required' => false,
                'placeholder' => 'Select your goal',
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('activityLevel', ChoiceType::class, [
                'label' => 'Activity Level',
                'choices' => [
                    'Sedentary (little or no exercise)' => 'sedentary',
                    'Lightly active (light exercise 1-3 days/week)' => 'light',
                    'Moderately active (moderate exercise 3-5 days/week)' => 'moderate',
                    'Very active (hard exercise 6-7 days/week)' => 'active',
                    'Extra active (very hard exercise & physical job)' => 'extra_active',
                ],
                'required' => false,
                'placeholder' => 'Select your activity level',
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
