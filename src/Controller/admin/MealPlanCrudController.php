<?php
// src/Controller/admin/MealPlanCrudController.php

namespace App\Controller\admin;

use App\Entity\MealPlan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
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
            // Update search fields to match what exists in your entity
            ->setSearchFields(['title', 'day'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // Show ID only on index
            IdField::new('id')->onlyOnIndex(),

            // Basic info
            TextField::new('title')->setLabel('Meal Plan Name'), // NOT 'name'
            TextEditorField::new('description')->setLabel('Description'),


            IntegerField::new('totalCalories')->setLabel('Total Calories'), // NOT 'dailyCalories'
            TextField::new('day')->setLabel('Day of Week'),


            // Associations that exist:
            AssociationField::new('user')->setRequired(true)->setLabel('User'),
            AssociationField::new('meals')->setLabel('Meals'),

            // Timestamps - only createdAt exists, not updatedAt
            DateTimeField::new('createdAt')->onlyOnIndex()->setLabel('Created At'),
        ];
    }
}
