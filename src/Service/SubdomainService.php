<?php

namespace App\Service;

use App\Entity\Subdomain;
use App\Infrastructure\Persistence\Doctrine\Repository\SubdomainRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class SubdomainService implements ServiceInterface
{
    private SubdomainRepository $subdomainRepository;
    private Security $security;

    public function __construct(
        SubdomainRepository $subdomainRepository,
        Security $security
        )
    {
        $this->subdomainRepository = $subdomainRepository;
        $this->security = $security;
    }
    public function findBy($searchBy, $item): array{
        return $this->subdomainRepository->findBy([$searchBy => $item]);
    }
    public function findOneBy($searchBy, $item): Subdomain{
        return $this->subdomainRepository->findOneBy([$searchBy => $item]);
    }
    public function remove($subdomain): bool{

        if (!$subdomain instanceof Subdomain) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }
        
        return $this->subdomainRepository->remove($subdomain);            
    }
    public function save($subdomain): bool{

        if (!$subdomain instanceof Subdomain) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }

        return $this->subdomainRepository->save($subdomain);            
    }
}