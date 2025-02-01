<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegistrationFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AccountUserController extends AbstractController
{
    private Security $security;
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(
        Security $security,
        UserPasswordHasherInterface $userPasswordHasher,
    )
    {
        $this->security = $security;
        $this->userPasswordHasherInterface =  $userPasswordHasher;
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
  
        $user = $this->security->getUser();

        return $this->render('User/Account/deleteAccount.html.twig', [
            'title' => 'Confirmar el borrado de tu cuenta de usuario'
        ]);
    }
}
