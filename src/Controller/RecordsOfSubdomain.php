<?php

namespace App\Controller;

use App\Entity\SubdomainRecord;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\SubdomainRecordFormType;
use Symfony\Bundle\SecurityBundle\Security;
use App\Service\RecordsOfSubdomainService;
use App\Service\SubdomainService;

final class RecordsOfSubdomain extends AbstractController
{
    private RecordsOfSubdomainService $recordsOfSubdomainService;
    private SubdomainService $subdomainService;
    private Security $security;

    public function __construct(
        RecordsOfSubdomainService $recordsOfSubdomainService, 
        SubdomainService $subdomainService,
        Security $security
        )
    {
        $this->recordsOfSubdomainService = $recordsOfSubdomainService;
        $this->subdomainService = $subdomainService;
        $this->security = $security;
    }

    public function addRecord(Request $request): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $subdomain =  $this->subdomainService->findOneBy('domain',$idSubdominio);
        $allRecordsData =  $this->recordsOfSubdomainService->findBy('subdomain' , $idSubdominio);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        $record = new SubdomainRecord();
        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
            $record->setSubdomain($subdomain); 
            $result = $this->recordsOfSubdomainService->save($record);            

            $this->addFlash($result ? 'success' : 'error', $result ? 'Registro añadido correctamente.' : 'Error creando el registro');

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

        $subdomain = $this->recordsOfSubdomainService->findOneBy('subdomain' , $idSubdominio);

        $checkUserPermissionsForThisAction = $this->security->getUser() === $subdomain->getSubdomain()->getUser();
        if(!$checkUserPermissionsForThisAction){
            throw $this->createNotFoundException('Not permissions for this action.');
        }

        $record = $this->recordsOfSubdomainService->findOneBy('id', $recordId);

        if (!$subdomain) {
            throw $this->createNotFoundException('Subdomain not found');
        }

        if (!$record) {
            throw $this->createNotFoundException('Record not found');
        }

        $form = $this->createForm(SubdomainRecordFormType::class, $record);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $this->recordsOfSubdomainService->save($form->getData());  

            $this->addFlash($result ? 'success' : 'error', $result ? 'Registro actualizado correctamente.' : 'Error actualizando el registro.');

            return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]);
        }

        return $this->render('Subdomain/Records/editRecord.html.twig', [
            'form' => $form->createView(),
            'subdomain' => $subdomain,
            'record' => $record,
            'title' => 'Editar registro'
        ]);
    }

    public function deleteRecord(Request $request): Response
    {
        $idSubdominio =  (int) $request->attributes->get('idSubdominio');
        $idRecord =  (int) $request->attributes->get('idRecord');
        $record = $this->recordsOfSubdomainService->findOneBy('id',$idRecord);
        
        $checkUserPermissionsForThisAction = $this->security->getUser() === $record->getSubdomain()->getUser();
        if(!$checkUserPermissionsForThisAction){
            throw $this->createNotFoundException('Not permissions for this action.');
        }

        $result = $this->recordsOfSubdomainService->remove($record);
        $this->addFlash($result ? 'success' : 'error', $result ? 'Registro borrado correctamente.': 'Error borrando el registro');
        return $this->redirectToRoute('front.v1.add.record.to.subdomain', ['idSubdominio' => $idSubdominio]);
    }

    
}


