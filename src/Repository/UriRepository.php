<?php

namespace App\Repository;

use App\Entity\Uri;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Uri|null find($id, $lockMode = null, $lockVersion = null)
 * @method Uri|null findOneBy(array $criteria, array $orderBy = null)
 * @method Uri[]    findAll()
 * @method Uri[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UriRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Uri::class);
    }

    // /**
    //  * @return Uri[] Returns an array of Uri objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Uri
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
