<?php

//Controleur pour voir la liste des utilisateurs
namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//Création de la page pour afficher la liste des utilisateurs
class UtilisateurListController extends AbstractController
{
    #[Route('/utilisateur', name: 'utilisateur_list')]
    public function listUtilisateurs(UtilisateurRepository $utilisateurRepository): Response
    {
        // Récupérer tous les utilisateurs depuis le repository
        $utilisateurs = $utilisateurRepository->findAll();

        // Rendre la vue avec les utilisateurs
        return $this->render('utilisateur_list/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}