<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vehicule>
 *
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public const VEHICULES_PER_PAGE = 9;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }


    public function getVehiculePaginator(array $filters = []): Paginator
    {
        if (!array_key_exists("page", $filters)) {
            $filters['page'] = 1;
        }

        $offset = ($filters['page'] - 1) * self::VEHICULES_PER_PAGE;
        $builder = $this->createQueryBuilder('v')
            ->orderBy('v.updatedAt', 'DESC')
            ->setMaxResults(self::VEHICULES_PER_PAGE)
            ->setFirstResult($offset)
        ;

        foreach ($filters as $key => $value) {
            $this->addCondition($builder, $key, $value);
        }
        return new Paginator($builder->getQuery());
    }

    private function addCondition(QueryBuilder $builder, string $key, ?string $value): void
    {
        # si la valeur est nulle ou une string vide, return
        if (!$value) return;

        if (str_ends_with($key, "Max")) {
            $predicate = '<=';
        } elseif (str_ends_with($key, "Min")){
            $predicate = '>=';
        } else return;

        $field = substr($key, 0, -3);

        $condition = 'v.'.$field.' '.$predicate.' :'.$key;
        $builder->andWhere($condition)
            ->setParameter($key, $value);
    }

    /**
     * @return Vehicule[] Returns an array of Vehicule objects
     */
    public function findAllWithLimit(int $max): array
    {
        return $this->createQueryBuilder('v')
            ->orderBy('v.updatedAt', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Vehicule[] Returns an array of Vehicule objects
//     */
//    public function findAll(): array
//    {
//        $entityManager = $this->getEntityManager();
//
//        $query = $entityManager->createQuery(
//            'SELECT p, c
//            FROM App\Entity\Product p
//            INNER JOIN p.category c
//            WHERE p.id = :id'
//        )->setParameter('id', $productId);
//
//        return $query->getOneOrNullResult();
//    }



//    /**
//     * @return Vehicule[] Returns an array of Vehicule objects
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

//    public function findOneBySomeField($value): ?Vehicule
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
