<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your email'
                ],
                'label' => 'Email Address',
                'label_attr' => ['class' => 'form-label fw-bold']
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your first name'
                ],
                'label' => 'First Name',
                'label_attr' => ['class' => 'form-label fw-bold']
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Enter your last name'
                ],
                'label' => 'Last Name',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false
            ])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Age',
                    'min' => 18,
                    'max' => 100
                ],
                'label' => 'Age',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false
            ])
            ->add('weight', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Weight in kg',
                    'min' => 30,
                    'max' => 300
                ],
                'label' => 'Weight (kg)',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false
            ])
            ->add('height', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Height in cm',
                    'min' => 100,
                    'max' => 250
                ],
                'label' => 'Height (cm)',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false
            ])
            ->add('goal', ChoiceType::class, [
                'choices' => [
                    'Weight Loss' => 'weight_loss',
                    'Muscle Gain' => 'muscle_gain',
                    'Maintain Weight' => 'maintain',
                    'Improve Fitness' => 'improve_fitness'
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label' => 'Fitness Goal',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false,
                'placeholder' => 'Select your goal'
            ])
            ->add('activityLevel', ChoiceType::class, [
                'choices' => [
                    'Sedentary (little or no exercise)' => 'sedentary',
                    'Lightly active (light exercise 1-3 days/week)' => 'lightly_active',
                    'Moderately active (moderate exercise 3-5 days/week)' => 'moderately_active',
                    'Very active (hard exercise 6-7 days/week)' => 'very_active',
                    'Extra active (very hard exercise & physical job)' => 'extra_active'
                ],
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label' => 'Activity Level',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false,
                'placeholder' => 'Select your activity level'
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Create a password (min. 6 characters)',
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Password',
                'label_attr' => ['class' => 'form-label fw-bold']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'label' => 'I agree to the terms and conditions',
                'label_attr' => ['class' => 'form-check-label']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
