<?php

namespace App\Controller;

use App\Entity\Domain;
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

        $form = $this->createForm(SubdomainFormType::class, $subdomain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subdomain = $form->getData(); 
            $subdomain->setUser($security->getUser());
            $subdomain->setCreatedAt(new \DateTime());
            $subdomain->setIsActive(false);
            $entityManager->persist($subdomain);
            $entityManager->flush();

            $this->addFlash('success', 'Subdomain created successfully.');

            return $this->redirectToRoute('subdomain_list');
        }

        return $this->render('Subdomain/addSubdomain.html.twig', [
            'form' => $form->createView(), 'title' => 'Añadir subdominio'
        ]);
    }

    public function listSubdomain(Request $request, EntityManagerInterface $entityManager,  Security $security): Response
    {
        $subdomainData =  $entityManager->getRepository(Subdomain::class)->findBy(['user' => $security->getUser()]);
        
        return $this->render('Subdomain/listSubdomain.html.twig', [
            'title' => 'Subdominios creados',
            'data' => $subdomainData
        ]);
    }
}
