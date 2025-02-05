<?php

namespace App\Service;

use App\Entity\User;
use App\Infrastructure\Persistence\Doctrine\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

final class UserService implements ServiceInterface
{
     public function __construct(
        private UserRepository $userRepository,
        private Security $security
        )
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }
    public function findBy($searchBy, $item): array{
        return $this->userRepository->findBy([$searchBy => $item]);
    }
    public function findOneBy($searchBy, $item): User{
        return $this->userRepository->findOneBy([$searchBy => $item]);
    }
    public function remove($user): bool{

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }

        return $this->userRepository->remove($user);            
    }
    public function save($user): bool{

        if (!$user instanceof User) {
            throw new \InvalidArgumentException('Expected instance of Subdomain');
        }

        return $this->userRepository->save($user);            
    }
}