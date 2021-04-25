<?php

namespace App\Repository;

use App\Entity\CommentBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentBlog[]    findAll()
 * @method CommentBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentBlog::class);
    }

    // /**
    //  * @return CommentBlog[] Returns an array of CommentBlog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentBlog
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
