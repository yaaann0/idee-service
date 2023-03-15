<?php

namespace App\Repository;

use App\Entity\WeeksheetState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeeksheetState|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeeksheetState|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeeksheetState[]    findAll()
 * @method WeeksheetState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeksheetStateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeeksheetState::class);
    }

    // /**
    //  * @return WeeksheetState[] Returns an array of WeeksheetState objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeeksheetState
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
