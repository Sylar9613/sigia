<?php

namespace App\Repository;

use App\Entity\MedidaDisciplinaria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MedidaDisciplinaria|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedidaDisciplinaria|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedidaDisciplinaria[]    findAll()
 * @method MedidaDisciplinaria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedidaDisciplinariaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedidaDisciplinaria::class);
    }

    // /**
    //  * @return MedidaDisciplinaria[] Returns an array of MedidaDisciplinaria objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MedidaDisciplinaria
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
