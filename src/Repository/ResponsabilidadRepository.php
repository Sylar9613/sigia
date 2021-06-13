<?php

namespace App\Repository;

use App\Entity\Responsabilidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Responsabilidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Responsabilidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Responsabilidad[]    findAll()
 * @method Responsabilidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponsabilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Responsabilidad::class);
    }

    // /**
    //  * @return Responsabilidad[] Returns an array of Responsabilidad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Responsabilidad
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
