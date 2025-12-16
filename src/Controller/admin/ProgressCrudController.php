<?php

namespace App\Controller\admin;

use App\Entity\Progress;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ProgressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Progress::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // Show weight as a number field
            NumberField::new('weight')
                ->setNumDecimals(1) // Shows 1 decimal place
                ->setHelp('Enter weight in kg'),

            // Photo upload field
            ImageField::new('photo')
                ->setBasePath('uploads/progress')
                ->setUploadDir('public/uploads/progress')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

            // Note as textarea
            TextareaField::new('note')
                ->setHelp('Optional notes about your progress')
                ->hideOnIndex(), // Hide on index page to save space

            // Created at - readonly
            DateTimeField::new('createdAt')
                ->hideOnForm(), // Can't edit creation date

            // User field - hide it from form since we set it automatically
            AssociationField::new('user')
                ->hideOnForm(), // Don't show on form
        ];
    }

    /**
     * This is the key fix - automatically set the current user
     * when creating a new Progress entity
     */
    public function createEntity(string $entityFqcn)
    {
        $progress = new Progress();

        // Automatically set the current logged-in user
        $user = $this->getUser();

        if ($user) {
            $progress->setUser($user);
        } else {
            // This shouldn't happen if your admin requires login
            // but it's good practice to handle it
            throw new \RuntimeException('No user is logged in.');
        }

        return $progress;
    }

    /**
     * Optional: Override persistEntity for extra safety
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Double-check that user is set
        if (!$entityInstance->getUser()) {
            $entityInstance->setUser($this->getUser());
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}
