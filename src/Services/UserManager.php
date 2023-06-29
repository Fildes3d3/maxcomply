<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function create(string $email, string $password, string $username, ?array $roles = ['ROLE_USER']): ?User
    {
        $existingUser = $this->existingUser($email);

        if ($existingUser) {
            return null;
        }

        $user = new User();

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );

        $user
            ->setEmail($email)
            ->setUsername($username)
            ->setPassword($hashedPassword)
            ->setRoles($roles)
        ;

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function existingUser(string $email): ?User
    {
        return $this->userRepository->findOneBy(['email' => $email]);
    }
}
