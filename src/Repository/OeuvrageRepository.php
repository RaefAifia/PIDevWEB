<?php

namespace App\Repository;

use App\Entity\Oeuvrage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oeuvrage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oeuvrage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oeuvrage[]    findAll()
 * @method Oeuvrage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvrageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvrage::class);
    }

    /**
     *
     */
   /*
    * public function search($nom)
    {
        $query = $this->getEntityManager()->createQueryBuilder('p')
            ->select('o')->from('src\Entity\Oeuvrage','p')->where('p.nom like :nom ')
            ->setParameter('nom','%'.$nom.'%')

            ->getQuery();
        $oeuvrage = $query->getResult();
        return $oeuvrage ;
    }
    */

    // /**
    //  * @return Oeuvrage[] Returns an array of Oeuvrage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /*
    public function findOneBySomeField($value): ?Oeuvrage
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
