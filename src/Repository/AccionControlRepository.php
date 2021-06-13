<?php

namespace App\Repository;

use App\Entity\AccionControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AccionControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccionControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccionControl[]    findAll()
 * @method AccionControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccionControlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccionControl::class);
    }

    // /**
    //  * @return AccionControl[] Returns an array of AccionControl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccionControl
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
