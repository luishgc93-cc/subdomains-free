<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Entity\Subdomain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subdomain>
 */
class SubdomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subdomain::class);
    }
}
