<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Entity\SubdomainRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
 
/**
 * @extends ServiceEntityRepository<SubdomainRecord>
 */
final class RecordsOfSubdomainRepository extends ServiceEntityRepository 
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubdomainRecord::class);
    }
    
    public function save(SubdomainRecord $entity, bool $flush = true): bool
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

    public function remove(SubdomainRecord $entity, bool $flush = true): bool
	{
		try{
			$this->getEntityManager()->remove($entity);

			if ($flush) {
				$this->getEntityManager()->flush();
			}
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
