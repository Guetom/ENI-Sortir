<?php

namespace App\Controller\Admin;

use App\Entity\Registration;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegistrationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Registration::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('inscription')
            ->setEntityLabelInPlural('inscriptions')
            ->setPageTitle('index', 'Gestion des inscriptions')
            ->setPageTitle('edit', 'Modification d\'une inscription')
            ->setPageTitle('new', 'Création d\'une inscription')
            ->setPageTitle('detail', 'Détail d\'une inscription')
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-user-plus')
                    ->addCssClass('btn')
                    ->setLabel('Créer une inscription');
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
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Informations sur les inscriptions')
                ->setIcon('fa-solid fa-user-plus'),
            AssociationField::new('outing', 'Sortie')
                ->setColumns(12)
                ->setRequired(true),
            AssociationField::new('participant', 'Participant')
                ->setColumns(12)
                ->setRequired(true),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $registration = new Registration();
        $registration->setRegistrationDate(new \DateTime());
        return $registration;
    }
}
