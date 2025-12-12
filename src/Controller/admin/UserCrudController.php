<?php
// src/Controller/admin/UserCrudController.php

namespace App\Controller\admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('User')
            ->setEntityLabelInPlural('Users')
            ->setSearchFields(['email', 'firstName', 'lastName'])
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPaginatorPageSize(20);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            // Personal Info
            EmailField::new('email'),
            TextField::new('firstName')->setLabel('First Name'),
            TextField::new('lastName')->setLabel('Last Name'),
            IntegerField::new('age'),

            // Physical Info
            IntegerField::new('weight')->setLabel('Weight (kg)'),
            IntegerField::new('height')->setLabel('Height (cm)'),

            // Fitness Goals
            ChoiceField::new('goal')
                ->setChoices([
                    'Weight Loss' => 'weight_loss',
                    'Muscle Gain' => 'muscle_gain',
                    'Maintenance' => 'maintenance',
                    'Endurance' => 'endurance',
                ])
                ->setLabel('Goal'),

            ChoiceField::new('activityLevel')
                ->setChoices([
                    'Sedentary' => 'sedentary',
                    'Light' => 'light',
                    'Moderate' => 'moderate',
                    'Active' => 'active',
                    'Very Active' => 'very_active',
                ])
                ->setLabel('Activity Level'),

            // Roles as ArrayField for proper handling
            ArrayField::new('roles')->setLabel('Roles'),

            BooleanField::new('isBanned')->setLabel('Banned'),

            // Timestamps
            DateTimeField::new('createdAt')->onlyOnIndex(),
            DateTimeField::new('updatedAt')->onlyOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
            //->add(Crud::PAGE_INDEX, Action::EDIT)
          //  ->add(Crud::PAGE_INDEX, Action::DELETE);
    }
}
