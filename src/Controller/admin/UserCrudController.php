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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // This method hashes the password before saving
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            // Get the plain password from the form
            $plainPassword = $entityInstance->getPassword();

            // Hash the password if it's not empty
            if (!empty($plainPassword)) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $entityInstance,
                    $plainPassword
                );
                $entityInstance->setPassword($hashedPassword);
            }
        }

        parent::persistEntity($entityManager, $entityInstance);
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
        $fields = [
            IdField::new('id')->onlyOnIndex(),

            // Personal Info
            EmailField::new('email'),
            TextField::new('firstName')->setLabel('First Name'),
            TextField::new('lastName')->setLabel('Last Name'),
            IntegerField::new('age'),
        ];

        // Add password field only on NEW and EDIT pages
        if (in_array($pageName, [Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $fields[] = TextField::new('password')
                ->setLabel('Password')
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyWhenCreating()
                ->setFormTypeOption('attr', ['autocomplete' => 'new-password']);
        }

        // Continue with the rest of the fields
        return array_merge($fields, [
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
        ]);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
        //->add(Crud::PAGE_INDEX, Action::EDIT)
        //->add(Crud::PAGE_INDEX, Action::DELETE);
    }
}
