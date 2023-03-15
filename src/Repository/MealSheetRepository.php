<?php

namespace App\Repository;

use App\Entity\MealSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MealSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealSheet[]    findAll()
 * @method MealSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealSheet::class);
    }

    // /**
    //  * @return MealSheet[] Returns an array of MealSheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MealSheet
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
