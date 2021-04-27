<?php


namespace App\Repository;

use App\Entity\User;
use App\Entity\Livraison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method Livraison|null find($id, $lockMode = null, $lockVersion = null)
 * @method Livraison|null findOneBy(array $criteria, array $orderBy = null)
 * @method Livraison[]    findAll()
 * @method Livraison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livraison::class);
    }

    public function findliv()
    {
        $query = $this
            ->createQueryBuilder('c')
            ->select( 'c')
            ->addOrderBy('c.livraisonId', 'DESC')
            ->setMaxResults(1);
        return $query->getQuery()->getResult();

    }


    public function findlivr()
    {
        $query = $this
            ->createQueryBuilder('c')
            ->select( 'c')
            ->addOrderBy('c.livraisonId', 'DESC')
            ->setMaxResults(1);
        return $query->getQuery()->getSingleResult();

    }




    public function findByExpField($value)
    {
        return $this->createQueryBuilder('lid')
            ->Where('lid.livraisonId like :val or lid.nom like :val or lid.prenom like :val or lid.numTel like :val or lid.adresse like :val or lid.etat like :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('lid.livraisonId', 'ASC')
            ->getQuery()
            ->getResult();
    }


}