<?php

namespace App\Controller;

use App\Entity\Subdomain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubdomainFormType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\SubdomainService;

final class SubdomainController extends AbstractController
{
    private SubdomainService $subdomainService;
    public function __construct(SubdomainService $subdomainService)
    {
        $this->subdomainService = $subdomainService;
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
            $this->subdomainService->save($subdomain);

            $this->addFlash('success', 'Subdominio creado correctamente.');

            return $this->redirectToRoute('subdomain_list');
        }

        return $this->render('Subdomain/addSubdomain.html.twig', [
            'form' => $form->createView(), 'title' => 'Añadir subdominio'
        ]);
    }

    public function listSubdomain(Request $request, EntityManagerInterface $entityManager,  Security $security): Response
    {
        $subdomainData = $this->subdomainService->findBy('user' , $security->getUser());
        
        return $this->render('Subdomain/listSubdomain.html.twig', [
            'title' => 'Subdominios creados',
            'data' => $subdomainData
        ]);
    }
}
