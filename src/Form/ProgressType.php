<?php

namespace App\Form;

use App\Entity\Progress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProgressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight', NumberType::class, [
                'label' => 'Weight (kg)',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your weight']),
                    new Positive(['message' => 'Weight must be a positive number']),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '75.5',
                    'step' => '0.1',
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Progress Photo',
                'required' => false,
                'mapped' => false, // This field is not mapped to the entity directly
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, GIF, WebP)',
                    ])
                ],
                'attr' => [
                    'class' => 'form-control',
                    'accept' => 'image/*',
                ]
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 4,
                    'placeholder' => 'Add any notes about your progress...',
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Progress::class,
        ]);
    }
}
