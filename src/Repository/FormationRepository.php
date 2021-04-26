<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Formation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formation[]    findAll()
 * @method Formation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }


    public function findSearch(SearchData $search): array{
        $query=$this
            ->createQueryBuilder('f');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('f.titre LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }
        if (!empty($search->min)) {
            $query = $query
                ->andWhere('f.prix >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('f.prix <= :max')
                ->setParameter('max', $search->max);
        }

        if (!empty($search->domaine)) {
            $query = $query
                ->andWhere(' f.domaine IN (:domaine)')
                ->setParameter('domaine', $search->domaine);
        }
        if (!empty($search->niveau)) {
            $query = $query
                ->andWhere(' f.niveau IN (:niveau)')
                ->setParameter('niveau', $search->niveau);
        }
           // ->select('f')
          //  ->join(f.doma)
        return $query->getQuery()->getResult();
    }

    public function findTitre($titre)
    {
        $q=$this->createQueryBuilder('f')
            ->where('f.titre LIKE :titre')
            ->setParameter(':titre',"%$titre%")
            ->orderBy('f.titre', 'ASC');

        return $q->getQuery()->getResult();
    }
    public function tri() {
        return $this
            ->createQueryBuilder('f')
            ->select( 'f')
            ->orderBy('f.date', 'ASC')
            ->getQuery()
            ->getResult();

    }
    public function mesFormations($formationId)
    {
        return $this
            ->createQueryBuilder('f')
            ->select('')
            ->where("f.user=:user")
            ->setParameter('formation', $formationId)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function affaccueuilF(): array
    {
        return $this
            ->createQueryBuilder('f')
            ->select( 'f')
            ->orderBy('f.formationId', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
       // $rep=$this->getDoctrine()->getRepository(Formation::class)->affaccueuilF();


    }
/*
     /**
     * @return Formation[] Returns an array of Formation objects
      */
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
    public function findOneBySomeField($value): ?Formation
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
   */
   /* public function findByExampleField(Formation $formation)
    {
        q = Doctrine_Query::create()
        ->update('Account')
        ->set('amount', 'amount + 200')
            ->where('f.formationId= :val');

    }*/
}
