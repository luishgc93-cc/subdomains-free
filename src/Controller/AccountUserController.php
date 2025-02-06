<?php

namespace App\Controller;

use App\Security\EmailVerifier;
use App\Service\EmailService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

final class AccountUserController extends AbstractController
{
        public function __construct(
        private Security $security,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EmailService $emailService,
        private UserService $userService,
        private EmailVerifier $emailVerifier,

        )
    {
        $this->security = $security;
        $this->userPasswordHasherInterface =  $userPasswordHasher;
        $this->emailService = $emailService;
        $this->userService = $userService;

    }
 
    public function accountUser(Request $request , EntityManagerInterface $entityManager){
        $user = $this->security->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $plainPassword));
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Contraseña actualizada correctamente.');
            return $this->redirectToRoute('front.v1.user.modify.account');
        }
    
        return $this->render('User/Account/profile.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => 'Modifica tu cuenta de usuario'
        ]);
    }
    public function deleteAccountUser(){
        $uuid = Uuid::v6();

        $user = $this->security->getUser();
        $this->emailService->sendEmailDeleteAccount($user, $uuid);

        return $this->render('User/Account/deleteAccount.html.twig', [
            'title' => 'Confirmar el borrado de tu cuenta de usuario'
        ]);
    }

    public  function confirmDeleteAccountUser(Request $request)
    {
        $user = $this->security->getUser();

        try {
            $this->emailVerifier->handleEmailForDeleteAccountUser($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('error', 'Error confirmando el borrado de su cuenta de usuario.');
            return $this->redirectToRoute('front.v1.add.subdomain');
        }

        return $this->render('User/Account/deleteAccountSuccess.html.twig', [
            'title' => 'Cuenta de usuario borrada correctamente'
        ]);
    }
}
