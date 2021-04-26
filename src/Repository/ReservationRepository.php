<?php


namespace App\Repository;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findOneBySomeField($value,$value2)
    {
        return $this->createQueryBuilder('l')
            ->Where('l.evenement = :val')
            ->andWhere('l.user = :val1')
            ->setParameter('val', $value)
            ->setParameter('val1', $value2)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}