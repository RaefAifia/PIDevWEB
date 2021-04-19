<?php

namespace App\Repository;


use App\Entity\FiltreOeuvre;
use App\Entity\Oeuvrage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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



    public function findsearch(FiltreOeuvre $search): array
   {
       return $this->getSearchQuery($search)->getQuery()->getResult();
   }

    /**
     * @return integer[]
     */
    public function findMinMax(FiltreOeuvre $search): array
    {
        $results = $this->getSearchQuery($search, true)
            ->select('MIN(o.prix) as min', 'MAX(o.prix) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'], (int)$results[0]['max']];
    }


    private function getSearchQuery(FiltreOeuvre $search, $ignorePrice = false): QueryBuilder
    {
        $query = $this
            ->createQueryBuilder('o')
            ->select( 'o')
            ->andWhere('o.isvalid = 1');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('o.nom LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->min) && $ignorePrice === false) {
            $query = $query
                ->andWhere('o.prix >= :min')
                ->setParameter('min',$search->min);
        }

        if (!empty($search->max) && $ignorePrice === false) {
            $query = $query
                ->andWhere('o.prix <= :max')
                ->setParameter('max',$search->max);
        }

        if (!empty($search->domaine)) {
            $query = $query
                ->andWhere('o.domaine IN (:domaines)')
                ->setParameter('domaines', $search->domaine);
        }


        return $query;
    }


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
