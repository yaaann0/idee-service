<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findAllQuery(ClientSearch $search, $isAdmin = null): Query
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (!$isAdmin) {
            $queryBuilder->where('p.archivedAt is NULL');
        }

        if ($search->getOrderBy()) {
            $queryBuilder->orderBy('p.'.$search->getOrderBy(), $search->getOrder());
        }

        if ($search->getFullname()) {
            $queryBuilder
                ->andWhere('p.fullname LIKE :fullname')
                ->setParameter('fullname', $search->getFullname().'%');
        }

        if ($search->getCity()) {
            $queryBuilder
                ->andWhere('p.city LIKE :city')
                ->setParameter('city', $search->getCity().'%');
        }

        if ($search->getPostalCode()) {
            $queryBuilder
                ->andWhere('p.postalCode LIKE :postalCode')
                ->setParameter('postalCode', $search->getPostalCode().'%');
        }

        return $queryBuilder->getQuery();
    }

    public function findActiveByName(string $fullname, int $limit)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.archivedAt IS NULL')
            ->andWhere('c.fullname LIKE :fullname')->setParameter('fullname', $fullname.'%')
            ->orderBy('c.fullname', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findActiveOneByName(string $fullname)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.archivedAt IS NULL')
            ->andWhere('c.fullname LIKE :fullname')->setParameter('fullname', $fullname.'%')
            ->orderBy('c.fullname', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    // /**
    //  * @return Client[] Returns an array of Client objects
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
    public function findOneBySomeField($value): ?Client
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
