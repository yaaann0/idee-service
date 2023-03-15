<?php

namespace App\Repository;

use App\Entity\VacationSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VacationSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method VacationSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method VacationSheet[]    findAll()
 * @method VacationSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacationSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VacationSheet::class);
    }

    // /**
    //  * @return VacationSheet[] Returns an array of VacationSheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VacationSheet
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
