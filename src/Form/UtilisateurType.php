<?php
// Entité du formulaire d'inscription
namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Form\FormError; // Pour les erreurs personnalisées
use Symfony\Component\Mailer\MailerInterface; // Pour l'envoi d'e-mails
use Symfony\Component\Mime\Email as MimeEmail; // Pour la création d'e-mails
use Symfony\Component\Mailer\Exception\TransportExceptionInterface; // Gestion des exceptions d'envoi d'e-mails

class UtilisateurType extends AbstractType
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('emailConfirmation', EmailType::class, [
                'label' => 'Confirmez votre email',
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('password', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.{8,})/',
                        'message' => 'Le mot de passe doit comporter au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.'
                    ]),
                ],
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }

    public function sendEmail(Utilisateur $utilisateur): void
    {
        try {
            // Créez l'e-mail à envoyer
            $email = (new MimeEmail())
                ->from('votre@email.com') // Adresse expéditeur
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
