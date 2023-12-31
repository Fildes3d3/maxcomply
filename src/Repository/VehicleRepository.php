<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicle>
 *
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function findVehicleMakersByType(string $type): array
    {
        $qb = $this->createQueryBuilder('v');

        return $qb
            ->select('v.make')
            ->where("JSON_EXTRACT(v.techData, :jsonPath) = :type")
            ->setParameter('jsonPath', '$.type')
            ->setParameter('type', $type)
            ->distinct()
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
