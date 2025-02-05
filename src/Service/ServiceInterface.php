<?php

namespace App\Service;
use App\Entity\Subdomain;
use App\Entity\SubdomainRecord;
use App\Entity\User;

interface ServiceInterface
{
    public function findBy($searchBy, $item): array;
    public function findOneBy($searchBy, $item): Subdomain|SubdomainRecord|User;
    public function remove($item): bool;
    public function save($item): bool;
}