<?php

namespace App\Tests\Support;

use App\Services\FixtureManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait KernelUtilsTrait
{
    public function getFixtureManager(ContainerInterface $container): FixtureManager
    {
        return $container->get(FixtureManager::class);
    }

    public function getEntityManager(ContainerInterface $container): EntityManagerInterface
    {
        return $container->get('doctrine.orm.entity_manager');
    }

    public function getJWTManager(ContainerInterface $container): JWTTokenManagerInterface
    {
        return $container->get(JWTTokenManagerInterface::class);
    }


    public function purgeTables(ContainerInterface $container, array $tables): void
    {
        $em = $this->getEntityManager($container);

        \array_walk(
            $tables,
            fn (string $table) => $em->getRepository($table)
                ->createQueryBuilder('d')
                ->delete()
                ->getQuery()
                ->execute()
        );

        $em->flush();
    }
}
