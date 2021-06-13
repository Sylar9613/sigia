<?php

namespace App\Repository;

use App\Entity\Phc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Phc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phc[]    findAll()
 * @method Phc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phc::class);
    }

    /**
     * @return Phc[] Returns an array of Phc objects
     */
    public function findByHc()
    {
        $em = $this->getEntityManager();
        $consulta2 = $em->getRepository('App\Entity\Phc')->findAll();
        /**
         * @var Collection|Phc[] $collection
         */
        $collection = new ArrayCollection();
        /**
         * @var Phc $item
         */
        foreach ($consulta2 as $item)
        {
            if ($item->getHc()==""){
                $collection->add($item);
            }
        }
        /*var_dump($collection);die;*/
        return $collection;
    }


    /*
    public function findOneBySomeField($value): ?Phc
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
