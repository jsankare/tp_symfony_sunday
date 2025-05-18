<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function sendResetPasswordEmail(string $to, string $token): void
    {
        $resetUrl = $this->urlGenerator->generate('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new Email())
            ->from('noreply@example.com')
            ->to($to)
            ->subject('Réinitialisation de votre mot de passe')
            ->html($this->getEmailTemplate($resetUrl));

        $this->mailer->send($email);
    }

    private function getEmailTemplate(string $resetUrl): string
    {
        return <<<HTML
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                <h2 style="color: #333;">Réinitialisation de votre mot de passe</h2>
                <p>Bonjour,</p>
                <p>Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour définir un nouveau mot de passe :</p>
                <p>
                    <a href="{$resetUrl}" style="display: inline-block; padding: 10px 20px; background-color: #00ffff; color: #000; text-decoration: none; border-radius: 4px;">
                        Réinitialiser mon mot de passe
                    </a>
                </p>
                <p>Ce lien expirera dans 1 heure.</p>
                <p>Si vous n'avez pas demandé cette réinitialisation, vous pouvez ignorer cet email.</p>
                <p>Cordialement,<br>L'équipe</p>
            </div>
        HTML;
    }
}
