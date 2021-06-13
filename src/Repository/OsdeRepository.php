<?php

namespace App\Repository;

use App\Entity\Osde;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Osde|null find($id, $lockMode = null, $lockVersion = null)
 * @method Osde|null findOneBy(array $criteria, array $orderBy = null)
 * @method Osde[]    findAll()
 * @method Osde[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OsdeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Osde::class);
    }

    // /**
    //  * @return Osde[] Returns an array of Osde objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Osde
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
