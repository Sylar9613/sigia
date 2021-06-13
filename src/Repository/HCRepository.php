<?php

namespace App\Repository;

use App\Entity\HC;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method HC|null find($id, $lockMode = null, $lockVersion = null)
 * @method HC|null findOneBy(array $criteria, array $orderBy = null)
 * @method HC[]    findAll()
 * @method HC[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HCRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HC::class);
    }

    /**
      * @return HC[] Returns an array of HC objects
      */

    public function findByMunicipio($value)
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT hc
                                     FROM App:HC hc 
                                     JOIN App:Phc phc
                                     JOIN App:Municipio mun
                                     WHERE hc.id=phc.hc
                                     AND phc.municipio=mun.id
                                     AND mun.id=:val
                                     AND hc.activo=1
                                     ')->setParameter('val', $value);
        //var_dump($consulta->getArrayResult());die;

        return $consulta->getArrayResult();
    }


    /*
    public function findOneBySomeField($value): ?HC
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
