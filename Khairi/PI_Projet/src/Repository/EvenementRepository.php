<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function findOneBySomeField($value,$value2): array
    {
        return $this->createQueryBuilder('l')
            ->Where('l.id_artiste = :val')
            ->andWhere('l.etat = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value2)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->Where('b.titre like :val or b.date_evenement like :val or b.capacite like :val or b.domaine like :val or b.prix like :val or b.description like :val 
             ')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('b.evenement_id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
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
    public function findOneBySomeField($value): ?Evenement
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
