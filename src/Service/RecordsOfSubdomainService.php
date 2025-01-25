<?php

namespace App\Service;

use App\Entity\SubdomainRecord;
use App\Infrastructure\Persistence\Doctrine\Repository\RecordsOfSubdomainRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class RecordsOfSubdomainService implements ServiceInterface
{
    private RecordsOfSubdomainRepository $recordsOfSubdomainRepository;
    private Security $security;

    public function __construct(
        RecordsOfSubdomainRepository $recordsOfSubdomainRepository,
        Security $security
        )
    {
        $this->recordsOfSubdomainRepository = $recordsOfSubdomainRepository;
        $this->security = $security;
    }
    public function findBy($searchBy, $item): array{
        return $this->recordsOfSubdomainRepository->findBy([$searchBy => $item]);
    }
    public function findOneBy($searchBy, $item): SubdomainRecord{
        return $this->recordsOfSubdomainRepository->findOneBy([$searchBy => $item]);
    }
    public function remove($subdomain): bool{

        if (!$subdomain instanceof SubdomainRecord) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }

        return $this->recordsOfSubdomainRepository->remove($subdomain);            
    }
    public function save($subdomain): bool{

        if (!$subdomain instanceof SubdomainRecord) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }

        return $this->recordsOfSubdomainRepository->save($subdomain);            
    }
}