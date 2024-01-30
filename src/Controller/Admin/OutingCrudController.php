<?php

namespace App\Controller\Admin;

use App\Entity\Outing;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use App\Repository\SiteRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OutingCrudController extends AbstractCrudController
{
    public function __construct(
        private SiteRepository $siteRepository
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return Outing::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('sortie')
            ->setEntityLabelInPlural('sorties')
            ->setPageTitle('index', 'Gestion des sorties')
            ->setPageTitle('edit', 'Modification d\'une sortie')
            ->setPageTitle('new', 'Création d\'une sortie')
            ->setPageTitle('detail', 'Détail d\'une sortie')
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action
                    ->setIcon('fa-solid fa-person-hiking')
                    ->addCssClass('btn')
                    ->setLabel('Créer une sortie');
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
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            FormField::addPanel('Informations sur la sortie')
                ->setIcon('fa-solid fa-person-hiking'),
            TextField::new('title', 'Nom de la sortie')
                ->setColumns(8),
            DateTimeField::new('startDate', 'Date et heure de la sortie')
                ->formatValue(function ($value, $entity) {
                    return $entity->getStartDate()->format('d/m/Y H:i');
                })
                ->setColumns(2),
            IntegerField::new('duration', 'Durée (en heure)')
                ->setColumns(2),
            TextareaField::new('description', 'Description')
                ->setColumns(8),
            DateTimeField::new('closingDate', 'Date limite d\'inscription')
                ->formatValue(function ($value, $entity) {
                    return $entity->getStartDate()->format('d/m/Y H:i');
                })
                ->setColumns(2),
            AssociationField::new('status', 'Statut')
                ->setColumns(2),
//            ImageField::new('poster', 'Image')
//                ->setBasePath('/uploads/images/outings')
//                ->setUploadDir('/public/uploads/images/outings')
//                ->setUploadedFileNamePattern('[randomhash].[extension]')
//                ->setColumns(8),
            FormField::addPanel('Lieu de la sortie')
                ->setIcon('fas fa-map-marker-alt'),
            AssociationField::new('place', 'Lieu')
                ->setColumns(8),
            NumberField::new('latitude', 'Latitude')
                ->onlyOnDetail()
                ->setColumns(2),
            NumberField::new('longitude', 'Longitude')
                ->onlyOnDetail()
                ->setColumns(2),
        ];
    }
}
