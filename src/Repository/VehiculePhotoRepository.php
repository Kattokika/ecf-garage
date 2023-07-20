<?php

namespace App\Repository;

use App\Entity\VehiculePhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VehiculePhoto>
 *
 * @method VehiculePhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehiculePhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehiculePhoto[]    findAll()
 * @method VehiculePhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculePhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehiculePhoto::class);
    }

//    /**
//     * @return VehiculePhoto[] Returns an array of VehiculePhoto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VehiculePhoto
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
