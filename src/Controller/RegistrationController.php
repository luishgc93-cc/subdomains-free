<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use App\Service\EmailService;
use App\Service\UserService;

final class RegistrationController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        private EmailService $emailService,
        private Security $security,
        private UserService $userService
        )
    {
        $this->emailService = $emailService;
        $this->security = $security;
        $this->userService = $userService;
    }

    public function registerUser(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setIsPremium(false);
            $this->userService->save($user);
            $this->emailService->sendEmailConfirmationAccount($user);

            return $this->security->login($user, 'debug.security.authenticator.form_login.main', 'main');
        }

        return $this->render('User/Registration/register.html.twig', [
            'registrationForm' => $form,
            'title' => 'Registrarse gratuitamente'
        ]);
    }

    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('front.v1.user.register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('front.v1.user.register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('front.v1.add.subdomain');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('front.v1.all.subdomain');
    }

    public function verifyForwardUserEmail(): Response
    {
        $user = $this->security->getUser();

        $this->emailService->sendEmailConfirmationAccount($user);

        $this->addFlash('success',  'Correo de activación de cuenta reenviado.');

        return $this->render('User/Registration/veryAccount.html.twig', [
            'title' => 'Registrarse gratuitamente',
            'removeButtonResendEmail' => true
        ]);
    }
}
