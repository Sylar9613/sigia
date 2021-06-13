<?php

namespace App\Repository;

use App\Entity\Entidad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Entidad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entidad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entidad[]    findAll()
 * @method Entidad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entidad::class);
    }

    // /**
    //  * @return Entidad[] Returns an array of Entidad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entidad
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
