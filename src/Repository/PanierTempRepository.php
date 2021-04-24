<?php

namespace App\Repository;

use App\Entity\PanierTemp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PanierTemp|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierTemp|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierTemp[]    findAll()
 * @method PanierTemp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierTempRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierTemp::class);
    }

    public function deletepant()
    {
        $query = $this
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.user=1');
        return $query->getQuery()->execute();

    }



    // /*
    //  * @return PanierTemp[] Returns an array of PanierTemp objects
    //  */

    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.oeuvrage = :val')
            ->setParameter('val', $value)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?PanierTemp
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
