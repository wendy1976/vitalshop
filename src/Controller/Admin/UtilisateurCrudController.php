<?php
# Controlleur crud pour afficher la liste des utilisateurs
namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Utilisateur;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Les utilisateurs')
            ->setEntityLabelInSingular('utilisateur')
            //->setDateFormat('d/m/Y', 'fr_FR')
            //->setTimeFormat('...')
            ->setPageTitle('index','Salon Beautiful - Administration des utilisateurs')
            
            // ...
        ;
    }
}

