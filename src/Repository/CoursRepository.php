<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Cours|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cours|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cours[]    findAll()
 * @method Cours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

     /**
    * @return Cours[] Returns an array of Cours objects
    */

   /* public function findByFormation()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.formation = :id')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/


    public function findByFor(Request $request){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT c FROM App\Entity\Cours c JOIN c.formation f where f.formationId = :id ");

        $query->setParameter('id',$request->attributes->get('id'));
       return  $query->getResult();

    }

    public function coursVisible($formationId) {
        return $this   //select count(cours_id) as nb from cours where formation_id=?
        ->createQueryBuilder('c')
            ->select( 'count(c.coursId)')
            ->where("c.formation=: formation")
            ->setParameter('formation', $formationId)
            ->getQuery()
            ->getSingleScalarResult();

    }

    /*
    public function findOneBySomeField($value): ?Cours
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
