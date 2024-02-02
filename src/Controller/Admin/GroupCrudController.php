<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Group::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('groupe')
            ->setEntityLabelInPlural('groupes')
            ->setPageTitle('index', 'Gestion des groupes')
            ->setPageTitle('edit', 'Modification d\'un groupe')
            ->setPageTitle('new', 'Création d\'un groupe')
            ->setPageTitle('detail', 'Détail d\'un groupe')
            ->showEntityActionsInlined()
            ->setSearchFields(['groupName', 'createdBy'])
            ->setDefaultSort(['groupName' => 'ASC', 'createdBy' => 'ASC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fa fa-users')
                    ->addCssClass('btn')
                    ->setLabel('Créer un groupe');
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
            FormField::addPanel('Informations sur le groupe')
                ->setIcon('fas fa-users'),
            TextField::new('groupName', 'Nom du groupe')
                ->setColumns(6),
            AssociationField::new('createdBy', 'Créé par')
                ->setColumns(6),
            CollectionField::new('Guests', 'Membres')
                ->hideOnForm()
                ->setColumns(12),
            AssociationField::new('Guests', 'Membres')
                ->onlyOnForms()
                ->autocomplete()
                ->setQueryBuilder(function ($queryBuilder) {
                    return $queryBuilder
                        ->where('entity.disable = 0')
                        ->andWhere('entity.id != :userCurrent')
                        ->setParameter('userCurrent', $this->getUser()->getId())
                        ->orderBy('entity.firstname', 'ASC', 'entity.lastname', 'ASC');
                })
                ->setFormTypeOption('by_reference', false)
                ->setColumns(12),
        ];
    }
}
