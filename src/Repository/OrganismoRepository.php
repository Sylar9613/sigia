<?php

namespace App\Repository;

use App\Entity\Organismo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Organismo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organismo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organismo[]    findAll()
 * @method Organismo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganismoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organismo::class);
    }

    // /**
    //  * @return Organismo[] Returns an array of Organismo objects
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
    public function findOneBySomeField($value): ?Organismo
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
