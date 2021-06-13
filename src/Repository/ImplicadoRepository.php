<?php

namespace App\Repository;

use App\Entity\Implicado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Implicado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Implicado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Implicado[]    findAll()
 * @method Implicado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImplicadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Implicado::class);
    }

     /**
      * @return Implicado[] Returns an array of Implicado objects
      */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.activo = 1')
            ->andWhere('i.hc = :val')
            ->setParameter('val', $value)
            ->orderBy('i.nombre', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Implicado
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
