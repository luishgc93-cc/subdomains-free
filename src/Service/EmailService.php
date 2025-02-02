<?php

namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\EmailVerifier;
use App\Entity\User;

final class EmailService
{
    public function __construct(
        private EmailVerifier $emailVerifier
        )
    {
    }

    public function sendEmailConfirmationAccount(User $user)
    {
        $this->emailVerifier->sendEmailConfirmation('front.v1.user.very.email', $user,
        (new TemplatedEmail())
            ->from(new Address('onboarding@resend.dev', 'onboarding@resend.dev'))
            ->to((string) $user->getEmail())
            ->subject('Confirma su Email')
            ->htmlTemplate('User/Registration/confirmation_email.html.twig')
        );
    }
}