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
use App\Infrastructure\Persistence\Doctrine\Repository\RecordsOfSubdomainRepository;
use App\Infrastructure\Persistence\Doctrine\Repository\SubdomainRepository;

final class RecordsOfSubdomain extends AbstractController
{
    private RecordsOfSubdomainRepository $recordsOfSubdomainRepository;
    private SubdomainRepository $subdomainRepository;

    public function __construct(
        RecordsOfSubdomainRepository $recordsOfSubdomainRepository, 
        SubdomainRepository $subdomainRepository)
    {
        $this->recordsOfSubdomainRepository = $recordsOfSubdomainRepository;
        $this->subdomainRepository = $subdomainRepository;
    }

    public function addRecord(Request $request, Security $security): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $subdomain =  $this->subdomainRepository->find($idSubdominio);
        $allRecordsData =  $this->recordsOfSubdomainRepository->findBy(['subdomain' => $idSubdominio]);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        $record = new SubdomainRecord();
        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $record->setSubdomain($subdomain); 
            $this->recordsOfSubdomainRepository->save($record);            

            $this->addFlash('success', 'Registro añadido correctamente.');

            return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]); 
        }

        return $this->render('Subdomain/Records/addRecord.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'title' => 'Añadir registros DNS',
            'allRecords' => $allRecordsData
        ]);
    }

    public function editRecord(Request $request, Security $security): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $recordId =  (int) $request->attributes->get('idRecord');

        $subdomain = $this->recordsOfSubdomainRepository->findOneBy(['subdomain' => $idSubdominio]);
        $record = $this->recordsOfSubdomainRepository->findOneBy(['id' => $recordId]);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        if (!$record) {
            throw $this->createNotFoundException('Record not found');
        }

        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->recordsOfSubdomainRepository->save($form->getData());  

            $this->addFlash('success', 'Registro actualizado correctamente.');

            return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]);
        }

        return $this->render('Subdomain/Records/editRecord.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'record' => $record,
            'title' => 'Editar registro'
        ]);
    }

    public function deleteRecord(Request $request, Security $security): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $idRecord =  (int) $request->attributes->get('idRecord');
        $record = $this->recordsOfSubdomainRepository->findOneBy(['id' => $idRecord]);
        $this->recordsOfSubdomainRepository->remove($record);
        $this->addFlash('success', 'Registro borrado correctamente.');
        return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]);
    }

    
}


