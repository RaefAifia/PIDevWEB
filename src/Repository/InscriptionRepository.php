<?php

namespace App\Repository;

use App\Entity\Formation;
use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Scalar\String_;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    // /**
    //  * @return Inscription[] Returns an array of Inscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }   //  ->where("i.formationId=".$formationId)
    */
    public function nbInscit($formationId)
    {
        return $this
        ->createQueryBuilder('i')
            ->select( 'count(i.inscriptionId)')
              ->where("i.formation=:formation")
            ->setParameter('formation', $formationId)
            ->getQuery()
            ->getSingleScalarResult();
       // dump($this);

    }

    public function findB($formation,$user)//select count(inscription_id) as nb from inscription where user_id=? and formation_id=?"
    {
        return $this->createQueryBuilder('i')
            ->select( 'count(i.inscriptionId)')
            ->where("i.formation=:formation")
            ->andWhere('i.user=:user')
            ->setParameters(['formation'=> $formation,'user'=>$user])
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function __toString():String
    {
        $formation= new Formation();

        return $this->nbInscit($formation->getFormationId());
        // TODO: Implement __toString() method.
    }

}
