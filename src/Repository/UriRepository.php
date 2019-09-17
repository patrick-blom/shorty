<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Uri;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
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

    /**
     * @param string $shortCode
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUriByShortCode(string $shortCode): ?Uri
    {
        return $this->createQueryBuilder('uri')
                    ->andWhere('uri.shortCode = :val')
                    ->setParameter('val', $shortCode)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param string $UrlHash
     *
     * @return Uri|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUriByShortOriginalHash(string $UrlHash): ?Uri
    {
        return $this->createQueryBuilder('uri')
                    ->andWhere('uri.UrlHash = :val')
                    ->setParameter('val', $UrlHash)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    /**
     * @param Uri $entity
     *
     * @return bool
     */
    public function saveUri(Uri $entity): bool
    {
        try {
            $manager = $this->getEntityManager();
            $manager->persist($entity);
            $manager->flush();
        } catch (ORMException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param Uri $entity
     *
     * @return bool
     */
    public function deleteUri(Uri $entity): bool
    {
        try {
            $manager = $this->getEntityManager();
            $manager->remove($entity);
            $manager->flush();
        } catch (ORMException $exception) {
            return false;
        }

        return true;
    }
}
