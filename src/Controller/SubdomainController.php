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
    private Security $security;
    public function __construct
    (
        SubdomainService $subdomainService, 
        Security $security
    )
    {
        $this->subdomainService = $subdomainService;
        $this->security = $security;
    }

    public function addSubdomain(Request $request): Response
    {
        $subdomain = new Subdomain();

        $form = $this->createForm(SubdomainFormType::class, $subdomain);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subdomain = $form->getData(); 
            $subdomain->setUser($this->security->getUser());
            $subdomain->setCreatedAt(new \DateTime());
            $subdomain->setIsActive(false);
            $result = $this->subdomainService->save($subdomain);

            $this->addFlash($result ? 'success' : 'error', $result ? 'Subdominio creado correctamente.' : 'Error al crear Subdominio');

            return $this->redirectToRoute('front.v1.all.subdomain');
        }

        return $this->render('Subdomain/addSubdomain.html.twig', [
            'form' => $form->createView(), 'title' => 'Añadir subdominio'
        ]);
    }
    public function editSubdomain(Request $request): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
 
        $subdomain = $this->subdomainService->findOneBy('id' , $idSubdominio);
 
        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        $checkUserPermissionsForThisAction = $this->security->getUser() === $subdomain->getUser();
        if(!$checkUserPermissionsForThisAction){
            throw $this->createNotFoundException('Not permissions for this action.');
        }

        $form = $this->createForm(SubdomainFormType::class, $subdomain);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->subdomainService->save($form->getData());  

            $this->addFlash($result ? 'success' : 'error', $result ? 'Subdominio actualizado correctamente.' : 'Error actualizando el subdominio.');

            return $this->redirectToRoute('front.v1.all.subdomain');
        }

        return $this->render('Subdomain/addSubdomain.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'title' => 'Editar subdominio'
        ]);
    }
    public function deleteSubdomain(Request $request): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $subdomain = $this->subdomainService->findOneBy('id',$idSubdominio);

        $checkUserPermissionsForThisAction = $this->security->getUser() === $subdomain->getUser();
        if(!$checkUserPermissionsForThisAction){
            throw $this->createNotFoundException('Not permissions for this action.');
        }

        $result = $this->subdomainService->remove($subdomain);
        
        $this->addFlash($result ? 'success' : 'error', $result ? 'Subdominio borrado correctamente.': 'Error borrando el subdominio');
        return $this->redirectToRoute('front.v1.all.subdomain');
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
