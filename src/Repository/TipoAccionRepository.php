<?php

namespace App\Repository;

use App\Entity\TipoAccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TipoAccion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoAccion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoAccion[]    findAll()
 * @method TipoAccion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoAccionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAccion::class);
    }

    // /**
    //  * @return TipoAccion[] Returns an array of TipoAccion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoAccion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
