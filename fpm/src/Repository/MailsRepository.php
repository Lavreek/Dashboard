<?php

namespace App\Repository;

use App\Entity\Mails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mails>
 *
 * @method Mails|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mails|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mails[]    findAll()
 * @method Mails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mails::class);
    }

    /**
     * @return Mails[] Returns an array of Mails objects
     */
    public function findBetween(string $start, string $end) : array
    {
        return $this->createQueryBuilder('m')
            ->andWhere("m.date BETWEEN '$start' AND '$end'")
//            ->orderBy('CONVERT(datetime, m.date, 103)', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Mails
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
