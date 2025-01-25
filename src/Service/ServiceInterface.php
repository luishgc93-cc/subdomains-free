<?php

namespace App\Service;

interface ServiceInterface
{
    public function findBy($searchBy, $item);
    public function findOneBy($searchBy, $item);
    public function remove($item);
    public function save($item);
}