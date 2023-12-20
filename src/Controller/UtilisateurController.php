<?php


// Controleur du formulaire d'inscription
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface; // Pour l'envoi d'e-mails
use Symfony\Component\Mime\Email as MimeEmail; // Pour la création d'e-mails
use Symfony\Component\Mailer\Exception\TransportExceptionInterface; // Gestion des exceptions d'envoi d'e-mails
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Form\FormError; // Pour les erreurs personnalisées

class UtilisateurController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/formutilisateur', name: 'formutilisateur')]
    public function index(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $utilisateur = new Utilisateur();
        $utilisateurform = $this->createForm(UtilisateurType::class, $utilisateur);

        $utilisateurform->handleRequest($request);

        if ($utilisateurform->isSubmitted() && $utilisateurform->isValid()) {
            // Récupérer les valeurs des champs d'email et de confirmation
            $email = $utilisateurform->get('email')->getData();
            $emailConfirmation = $utilisateurform->get('emailConfirmation')->getData();

            // Vérifier si les champs d'email et de confirmation correspondent
            if ($email !== $emailConfirmation) {
                $utilisateurform->get('email')->addError(new FormError('Les adresses email doivent correspondre.'));
                $utilisateurform->get('emailConfirmation')->addError(new FormError('Les adresses email doivent correspondre.'));
            } else {
                // Les champs d'email et de confirmation correspondent, traitez l'inscription
                $entityManager = $doctrine->getManager();
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                // Envoyer un e-mail à l'utilisateur
                $this->sendEmail($utilisateur);

                // Redirigez l'utilisateur vers la page de succès d'inscription
                return $this->redirectToRoute('inscription_succes');
            }
        }

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurform' => $utilisateurform->createView(),
        ]);
    }

    #[Route('/inscription/success', name: 'inscription_succes')]
    public function inscriptionSucces(): Response
    {
        return $this->render('utilisateur/inscription_succes.html.twig');
    }

    private function sendEmail(Utilisateur $utilisateur): void
    {
        try {
            // Créez l'e-mail à envoyer
            $email = (new MimeEmail())
                ->from('caroline.ferru@free.fr') // Adresse expéditeur
                ->to($utilisateur->getEmail()) // Adresse destinataire
                ->subject('Confirmation d\'inscription')
                ->text('Merci de vous être inscrit.');

            // Envoyer l'e-mail
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Gestion des erreurs d'envoi de l'e-mail ici
            // Vous pouvez logger l'erreur ou afficher un message d'erreur à l'utilisateur
        }
    }
}
