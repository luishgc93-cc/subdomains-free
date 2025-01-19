<?php

namespace App\Controller;

use App\Entity\Subdomain;
use App\Entity\SubdomainRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubdomainRecordFormType;
use Symfony\Bundle\SecurityBundle\Security;
  
final class RecordsOfSubdomain extends AbstractController
{
    public function __construct()
    {
    }

    public function addRecord(Request $request, EntityManagerInterface $entityManager,  Security $security): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $subdomain =  $entityManager->getRepository(Subdomain::class)->find($idSubdominio);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        $record = new SubdomainRecord();
        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $record->setSubdomain($subdomain); 
            $entityManager->persist($record);            
            $entityManager->flush();                    

            $this->addFlash('success', 'Record created successfully.');

            return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]); 
        }

        return $this->render('Subdomain/Records/AddRecord.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'title' => 'Añadir registros DNS'
        ]);
    }

    public function editRecord(Request $request, EntityManagerInterface $em): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $recordId =  (int) $request->attributes->get('idRecord');

        $subdomain = $em->getRepository(Subdomain::class)->find($idSubdominio);
        $record = $em->getRepository(SubdomainRecord::class)->find($recordId);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        if (!$record) {
            throw $this->createNotFoundException('Record not found');
        }

        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();  

            $this->addFlash('success', 'Record updated successfully.');

            return $this->redirectToRoute('front.v1.add.records.on.subdomain', ['idSubdominio' => $idSubdominio]);
        }

        return $this->render('Subdomain/Records/editRecord.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'record' => $record,
        ]);
    }
    
}


