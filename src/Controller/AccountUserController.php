<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class AccountUserController extends AbstractController
{
        public function __construct(
        private Security $security,
        private UserPasswordHasherInterface $userPasswordHasher,
        private EmailService $emailService,
        )
    {
        $this->security = $security;
        $this->userPasswordHasherInterface =  $userPasswordHasher;
        $this->emailService = $emailService;
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
}
