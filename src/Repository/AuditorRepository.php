<?php

namespace App\Repository;

use App\Entity\Auditor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Auditor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auditor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auditor[]    findAll()
 * @method Auditor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auditor::class);
    }

    public function findPlazasCub()
    {
        $em = $this->getEntityManager();
        $consulta = $em->createQuery('SELECT cu
                                     FROM App:Auditor cu 
                                     JOIN App:Plaza ec
                                     WHERE cu.entidad=ec.entidad
                                     AND cu.cargo=ec.cargo
                                     AND cu.activo=1
                                     ');
        //var_dump($consulta->getArrayResult());die;

        return $consulta->getArrayResult();
    }

    // /**
    //  * @return Auditor[] Returns an array of Auditor objects
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
    public function findOneBySomeField($value): ?Auditor
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
