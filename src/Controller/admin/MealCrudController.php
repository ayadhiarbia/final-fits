<?php
// src/Controller/admin/MealCrudController.php

namespace App\Controller\admin;

use App\Entity\Meal;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class MealCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Meal::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Meal')
            ->setEntityLabelInPlural('Meals')
            // FIXED: Use 'title' instead of 'name' for search
            ->setSearchFields(['title', 'description'])
            // FIXED: Sort by 'title' instead of 'name'
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title')
                ->setLabel('Meal Name')
                ->setRequired(true),
            TextareaField::new('description')
                ->hideOnIndex(),
            ChoiceField::new('type')
                ->setLabel('Meal Type')
                ->setChoices([
                    'Breakfast' => 'breakfast',
                    'Lunch' => 'lunch',
                    'Dinner' => 'dinner',
                    'Snack' => 'snack',
                ])
                ->setRequired(true),
            IntegerField::new('calories')
                ->setRequired(true)
                ->setHelp('Total calories for this meal'),
            AssociationField::new('user')
                ->setRequired(true),
            DateTimeField::new('createdAt')
                ->onlyOnDetail()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }
}
