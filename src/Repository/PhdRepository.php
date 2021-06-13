<?php

namespace App\Repository;

use App\Entity\Phd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Phd|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phd|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phd[]    findAll()
 * @method Phd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phd::class);
    }

    // /**
    //  * @return Phd[] Returns an array of Phd objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Phd
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
