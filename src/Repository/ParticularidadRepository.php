<?php

namespace App\Repository;

use App\Entity\Particularidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Particularidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Particularidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Particularidad[]    findAll()
 * @method Particularidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticularidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Particularidad::class);
    }

    // /**
    //  * @return Particularidad[] Returns an array of Particularidad objects
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
    public function findOneBySomeField($value): ?Particularidad
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
