<?php

namespace App\Controller\Admin;

use App\Entity\City;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('ville')
            ->setEntityLabelInPlural('villes')

            ->setPageTitle('index', 'Gestion des villes')
            ->setPageTitle('edit', 'Modification d\'une ville')
            ->setPageTitle('new', 'Création d\'une ville')
            ->setPageTitle('detail', 'Détail d\'une ville')

            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-map-pin')
                    ->addCssClass('btn')
                    ->setLabel('Créer une ville');
            })
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action
                    ->setIcon('fa fa-edit')
                    ->addCssClass('btn')
                    ->setLabel('Modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action
                    ->setIcon('fa fa-trash')
                    ->addCssClass('btn btn-danger')
                    ->setLabel('Supprimer');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setIcon('fa fa-save')
                    ->addCssClass('btn')
                    ->setLabel('Ajouter');
            })
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setIcon('fa fa-save')
                    ->addCssClass('btn')
                    ->setLabel('Enregistrer');
            })
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->update(Crud::PAGE_INDEX, Action::DETAIL, function (Action $action) {
                return $action
                    ->setIcon('fa fa-eye')
                    ->addCssClass('btn')
                    ->setLabel('Détail');
            })
            ->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
                return $action
                    ->setIcon('fa fa-trash')
                    ->addCssClass('btn btn-danger')
                    ->setLabel('Supprimer');
            })
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations ville')
                ->setIcon('fa-solid fa-map-pin'),
            TextField::new('name', 'Nom de la ville')
                ->setColumns(6),
            TextField::new('postcode', 'Code postal')
                ->setMaxLength(5)
                ->setColumns(6),
        ];
    }
}
