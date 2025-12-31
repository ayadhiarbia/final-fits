<?php

namespace App\Controller\admin;

use App\Entity\Progress;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'User Progress')
            ->setPageTitle('detail', 'Progress Details')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        // Remove the "Add" action - this prevents admin from creating new progress
        return $actions
            ->disable(Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->onlyOnDetail(),

            NumberField::new('weight')
                ->setNumDecimals(1)
                ->formatValue(function ($value) {
                    return $value . ' kg';
                })
                ->hideOnForm(),

            ImageField::new('photo')
                ->setBasePath('uploads/progress')
                ->setRequired(false)
                ->hideOnForm()
                ->formatValue(function ($value) {
                    if (!$value) {
                        return 'No photo';
                    }
                    return sprintf('<img src="%s" width="50" height="50" style="object-fit: cover;">', $value);
                }),

            TextareaField::new('note')
                ->hideOnIndex()
                ->hideOnForm(),

            DateTimeField::new('createdAt')
                ->hideOnForm(),

            AssociationField::new('user')
                ->hideOnForm(),
        ];
    }
}
