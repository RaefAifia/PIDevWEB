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

}