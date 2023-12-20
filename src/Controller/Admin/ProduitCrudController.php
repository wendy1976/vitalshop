<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Repository\ProduitRepository;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Les fiches produits')
            ->setEntityLabelInSingular('Le produit')
            //->setDateFormat('d/m/Y', 'fr_FR')
            //->setTimeFormat('...')
            ->setPageTitle('index','Vitalshop - Les fiches produits')
            
            // ...
        ;
    }

    
}
