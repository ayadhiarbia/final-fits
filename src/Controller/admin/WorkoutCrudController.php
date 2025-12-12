<?php
// src/Controller/admin/WorkoutCrudController.php

namespace App\Controller\admin;

use App\Entity\Workout;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class WorkoutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Workout::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Workout')
            ->setEntityLabelInPlural('Workouts')
            // FIXED: Changed from 'name' to 'title' for search
            ->setSearchFields(['title', 'description'])
            // FIXED: Changed from 'name' to 'title' for sorting
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // ID only on index
            IdField::new('id')->onlyOnIndex(),

            // Basic info - CHANGED: TextField::new('title') not 'name'
            TextField::new('title')
                ->setLabel('Workout Name')
                ->setRequired(true),

            TextareaField::new('description')
                ->setLabel('Description')
                ->hideOnIndex(),

            // Difficulty - CHANGED: 'level' not 'difficulty'
            ChoiceField::new('level')
                ->setChoices([
                    'Beginner' => 'beginner',
                    'Intermediate' => 'intermediate',
                    'Advanced' => 'advanced',
                ])
                ->setLabel('Difficulty')
                ->setRequired(true),

            // Duration and calories
            IntegerField::new('duration')
                ->setLabel('Duration (minutes)')
                ->setRequired(true),

            IntegerField::new('caloriesBurned')
                ->setLabel('Estimated Calories Burned')
                ->setRequired(true),

            // Type - CHANGED: 'type' not 'equipment'
            ChoiceField::new('type')
                ->setChoices([
                    'Cardio' => 'cardio',
                    'Strength' => 'strength',
                    'Yoga' => 'yoga',
                    'Pilates' => 'pilates',
                    'HIIT' => 'hiit',
                    'CrossFit' => 'crossfit',
                ])
                ->setLabel('Workout Type')
                ->setRequired(true),

            // Video URL - Optional if you want to add this field later
            // UrlField::new('videoUrl')->setLabel('Video URL')->hideOnIndex(),

            // Instructions - Optional if you want to add this field later
            // TextareaField::new('instructions')->setLabel('Instructions')->hideOnIndex(),
        ];
    }
}
