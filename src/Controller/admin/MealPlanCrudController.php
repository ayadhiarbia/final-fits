<?php
// src/Controller/admin/MealPlanCrudController.php

namespace App\Controller\admin;

use App\Entity\MealPlan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class MealPlanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MealPlan::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Meal Plan')
            ->setEntityLabelInPlural('Meal Plans')
            ->setSearchFields(['name', 'description'])
           ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Show ID only on index
            IdField::new('id')->onlyOnIndex(),

            // Basic info
            TextField::new('name')->setLabel('Meal Plan Name'),
            TextEditorField::new('description')->setLabel('Description'),

            // Diet type choice
            ChoiceField::new('dietType')
                ->setChoices([
                    'Balanced' => 'balanced',
                    'Keto' => 'keto',
                    'Vegetarian' => 'vegetarian',
                    'Vegan' => 'vegan',
                    'Low Carb' => 'low_carb',
                    'High Protein' => 'high_protein',
                ])
                ->setLabel('Diet Type'),

            // Duration and nutrition
            IntegerField::new('durationDays')->setLabel('Duration (days)'),
            NumberField::new('dailyCalories')->setLabel('Daily Calories'),
            NumberField::new('proteinRatio')->setLabel('Protein %'),
            NumberField::new('carbRatio')->setLabel('Carbs %'),
            NumberField::new('fatRatio')->setLabel('Fat %'),

            // Associations
            AssociationField::new('user')->setRequired(false)->setLabel('Assigned User'),
            AssociationField::new('meals')->autocomplete()->setLabel('Meals'),

            // Timestamps, only on index
            DateTimeField::new('createdAt')->onlyOnIndex()->setLabel('Created At'),
            DateTimeField::new('updatedAt')->onlyOnIndex()->setLabel('Updated At'),
        ];
    }
}
