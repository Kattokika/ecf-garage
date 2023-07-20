<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 *
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public const AVIS_PER_PAGE = 2;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    /**
     * @return Avis[] Returns an array of Avis objects
     */
    public function findAllByStatus(string $status, int $max): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', $status)
            ->orderBy('a.date_visite', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Returns the count of Avis where the 'status' is "submitted"
     */
    public function getSubmittedAmount(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT COUNT(a.id) as cnt_notes
            FROM App\Entity\Avis a
            WHERE a.status = 'submitted'"
        );

        // returns the count of Avis where the status is "submitted"
        try {
            return (int)$query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

    /**
     * @return int Returns the average of Avis 'note' where the 'status' is "accepted"
     */
    public function getAverageRate(): float
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT AVG(a.note) as avg_notes
            FROM App\Entity\Avis a
            WHERE a.status = 'accepted'"
        );

        // returns the average of Avis 'note' where the status is "accepted"
        try {
            return (float)$query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

    public function getAvisPaginator(int $page, ?bool $showAll = false): Paginator
    {
        $offset = ($page - 1) * self::AVIS_PER_PAGE;
        $builder = $this->createQueryBuilder('m')
            ->orderBy('m.date_visite', 'DESC')
            ->setMaxResults(self::AVIS_PER_PAGE)
            ->setFirstResult($offset)
        ;
        if (!$showAll) {
            $builder->andWhere('m.status = :status')
                ->setParameter('status', 'submitted')
            ;
        }

        return new Paginator($builder->getQuery());
    }

//    /**
//     * @return Avis[] Returns an array of Avis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Avis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
