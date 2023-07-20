<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public const MESSAGES_PER_PAGE = 2;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getMessagePaginator(int $page, ?bool $onlyNonLu = false): Paginator
    {
        $offset = ($page- 1) * self::MESSAGES_PER_PAGE;
        $builder = $this->createQueryBuilder('m')
            ->orderBy('m.sent_at', 'DESC')
            ->setMaxResults(self::MESSAGES_PER_PAGE)
            ->setFirstResult($offset)
        ;
        if ($onlyNonLu) {
            $builder->andWhere('m.lu = false');
        }

        return new Paginator($builder->getQuery());
    }


    /**
     * @return int Returns the count of Message where the 'lu' is false
     */
    public function getUnreadAmount(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            "SELECT COUNT(m.id) as cnt_notes
            FROM App\Entity\Message m
            WHERE m.lu = false"
        );

        // returns the count of Message where the 'lu' is false
        try {
            return (int)$query->getSingleScalarResult();
        } catch (NoResultException|NonUniqueResultException $e) {
            return 0;
        }
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
