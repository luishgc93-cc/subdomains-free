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

    public function save(Subdomain $entity, bool $flush = true): bool
	{
		try{
			$this->getEntityManager()->persist($entity);

			if ($flush) {
				$this->getEntityManager()->flush();
			}
			return true;

		}catch(\Exception $e){
			return false;
		}
	}

	public function remove(Subdomain $entity, bool $flush = true): bool
	{
		try{
			$this->getEntityManager()->remove($entity);

			if ($flush) {
				$this->getEntityManager()->flush();
			}
			return true;

		}catch(\Exception $e){
			return false;
		}
	}
}
