<?php

namespace App\Repository;

use App\Entity\VehiculeCarburant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehiculeCarburant>
 *
 * @method VehiculeCarburant|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehiculeCarburant|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehiculeCarburant[]    findAll()
 * @method VehiculeCarburant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeCarburantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehiculeCarburant::class);
    }

//    /**
//     * @return VehiculeCarburant[] Returns an array of VehiculeCarburant objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VehiculeCarburant
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
