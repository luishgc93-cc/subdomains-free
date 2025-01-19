<?php

namespace App\Controller;

use App\Entity\Subdomain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubdomainFormType;
use Symfony\Bundle\SecurityBundle\Security;
  
final class SubdomainController extends AbstractController
{
    public function __construct()
    {
    }

    public function addSubdomain(Request $request, EntityManagerInterface $entityManager,  Security $security): Response
    {
        $subdomain = new Subdomain();
        $subdomain->setCreatedAt(new \DateTimeImmutable());  
        $form = $this->createForm(SubdomainFormType::class, $subdomain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $security->getUser();  
            $subdomain->setUser($user);
            $entityManager->persist($subdomain);
            $entityManager->flush();

            $this->addFlash('success', 'Subdomain created successfully!');

            return $this->redirectToRoute('front.v1.add.subdomain');  
        }

        return $this->render('subdomain/addSubdomain.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
