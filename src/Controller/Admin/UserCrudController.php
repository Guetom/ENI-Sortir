<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('utilisateur')
            ->setEntityLabelInPlural('utilisateurs')

            ->setPageTitle('index', 'Gestion des utilisateurs')
            ->setPageTitle('edit', 'Modification d\'un utilisateur')
            ->setPageTitle('new', 'Création d\'un utilisateur')
            ->setPageTitle('detail', 'Détail d\'un utilisateur')

            ->showEntityActionsInlined()

            ->setSearchFields(['firstname', 'lastname', 'email'])
            ->setDefaultSort(['firstname' => 'ASC', 'lastname' => 'ASC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fa fa-user')
                    ->addCssClass('btn')
                    ->setLabel('Créer un utilisateur');
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
            FormField::addPanel('Informations personnelles')
                ->setIcon('fas fa-user'),
            TextField::new('firstname', 'Prénom')
                ->setColumns(4),
            TextField::new('lastname', 'Nom')
                ->setColumns(4),
            TextField::new('pseudo', 'Pseudo')
                ->setDisabled($pageName != Crud::PAGE_NEW) // not disabled on the creation page
                ->setColumns(4),
            TextField::new('email', 'Email')
                ->setDisabled($pageName != Crud::PAGE_NEW) // not disabled on the creation page
                ->setColumns(8),
            AssociationField::new('site', 'Site')
                ->setColumns(2),
            BooleanField::new('disable', 'Compte désactivé')
                ->hideWhenCreating()
                ->setColumns(2),
            ImageField::new('profilePicture', 'Photo de profil')
                ->setBasePath('/uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setColumns(4),
        ];
    }
}
