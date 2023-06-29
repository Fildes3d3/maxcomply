<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Entity\Vehicle\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;
use Faker\Factory;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FixtureManager
{
    private Generator $fakerGenerator;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        $this->fakerGenerator = Factory::create();
    }

    public function createUser(array $options = []): User
    {
        $resolver = new OptionsResolver();
        $resolvedOptions = $resolver
            ->setDefined([
                'email',
                'password',
                'roles',
                'username',
            ])
            ->setAllowedTypes('roles', 'array')
            ->setDefaults([
                'email' => $this->fakerGenerator->email(),
                'password' => $this->fakerGenerator->password(),
                'roles' => ['ROLE_USER'],
                'username' => $this->fakerGenerator->name(),
            ])
            ->resolve($options)
        ;

        $user = new User();
        $user
            ->setEmail($resolvedOptions['email'])
            ->setPassword($resolvedOptions['password'])
            ->setRoles($resolvedOptions['roles'])
            ->setUsername($resolvedOptions['password'])
        ;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    public function createVehicle(array $options = []): Vehicle
    {
        $resolver = new OptionsResolver();
        $resolvedOptions = $resolver
            ->setDefined([
                'make',
                'techData',
            ])
            ->setAllowedTypes('techData', 'array')
            ->setDefaults([
                'make' => $options['make'] ?? $this->fakerGenerator->name(),
                'techData' => function (Options $options) {
                    return [
                        'topSpeed' => $this->fakerGenerator->numberBetween(200, 300),
                        'dimensions' => [
                            'width' => $this->fakerGenerator->numberBetween(1.5, 2),
                            'length' => $this->fakerGenerator->numberBetween(2, 6),
                            'height' => $this->fakerGenerator->numberBetween(1, 2),
                        ],
                        'engineData' => [
                            'displacement' => $this->fakerGenerator->numberBetween(900, 6000),
                            'power' => $this->fakerGenerator->numberBetween(60, 1000),
                        ],
                        'type' => isset($options['techData']['type'])
                            ?? $this->fakerGenerator->randomElement([
                                VehicleType::Electric,
                                VehicleType::Hybrid,
                                VehicleType::InternalCombustion,
                            ])
                    ];
                }
            ])
            ->resolve($options)
        ;

        $vehicle = new Vehicle();
        $vehicle
            ->setMake($resolvedOptions['make'])
            ->setTechData($resolvedOptions['techData'])
        ;

        $this->em->persist($vehicle);
        $this->em->flush();

        return $vehicle;
    }
}
