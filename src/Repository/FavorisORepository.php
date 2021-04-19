<?php

namespace App\Repository;

use App\Entity\FavorisO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavorisO|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavorisO|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavorisO[]    findAll()
 * @method FavorisO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavorisORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavorisO::class);
    }

    public function searcho(User $user)
    {
        return $this->getEntityManager()
            ->createQuery(

                "SELECT f 
                FROM App\Entity\FavorisO f 
                WHERE f.user = '$user'"
            )
            ->getResult();



    }


    // /**
    //  * @return FavorisO[] Returns an array of FavorisO objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FavorisO
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
