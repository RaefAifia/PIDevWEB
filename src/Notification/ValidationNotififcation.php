<?php


namespace App\Notification;

use Swift_Message;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
class ValidationNotififcation
{
    /**
     * Propriété contenant le module d'envoi de mail
     *
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Propriété contenant l'environnement twig
     *
     * @var Environment
     */
    private $renderer;

    /**
     * Constructeur de classe
     * @param Swift_Mailer $mailer
     * @param Environment $renderer
     */
    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * Méthode de notification (envoi de mail)
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function notify()
    {
        // On construit le mail
        $message = (new Swift_Message('Mon Blog - Nouvelle inscription'))
            // Expéditeur
            ->setFrom('no-reply@monblog.fr')
            // Destinataire
            ->setTo('contact@monblog.fr')
            // Corps du message (créé avec twig)
            ->setBody(
                $this->renderer->render(
                    'formation/NotifierClient.html.twig'
                ),
                'text/html'
            );

        // On envoie le mail
        $this->mailer->send($message);
    }
}