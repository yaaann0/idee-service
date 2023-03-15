<?php

namespace App\Repository;

use App\Entity\SheetState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SheetState|null find($id, $lockMode = null, $lockVersion = null)
 * @method SheetState|null findOneBy(array $criteria, array $orderBy = null)
 * @method SheetState[]    findAll()
 * @method SheetState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SheetState::class);
    }

    // /**
    //  * @return SheetState[] Returns an array of SheetState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SheetState
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
