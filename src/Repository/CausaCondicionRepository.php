<?php

namespace App\Repository;

use App\Entity\CausaCondicion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CausaCondicion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CausaCondicion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CausaCondicion[]    findAll()
 * @method CausaCondicion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CausaCondicionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CausaCondicion::class);
    }

    // /**
    //  * @return CausaCondicion[] Returns an array of CausaCondicion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CausaCondicion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
