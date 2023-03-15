<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Weeksheet;
use App\Entity\WeeksheetSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Weeksheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weeksheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weeksheet[]    findAll()
 * @method Weeksheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeeksheetRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Weeksheet::class);
	}

	public function findByUserQuery(User $user, string $orderBy, string $order)
	{
		return $this->createQueryBuilder('p')
			->where('p.user = :user')->setParameters(['user' => $user])
			->orderBy('p.'.$orderBy, $order)
			->getQuery();
	}

	public function findExistingSheet(User $user, \DateTime $beginAt)
	{
		return $this->createQueryBuilder('p')
			->where('p.user = :user')
			->andWhere('p.beginAt = :beginAt')
			->setParameters(array(
				'user' => $user,
				'beginAt' => $beginAt
				))
			->getQuery()
			->getOneOrNullResult()
		;
	}

	public function findAllQuery(WeeksheetSearch $search): Query
    {
		$queryBuilder = $this->_em->createQueryBuilder()
			->select('w, u')
			->from('App\Entity\Weeksheet', 'w')
			->join('w.user', 'u');

        if ($orderBy = $search->getOrderBy()) {
			if ($orderBy === 'lastname' || $orderBy === 'department') {
				$queryBuilder->orderBy('u.'.$orderBy, $search->getOrder());
			} else  {
				$queryBuilder->orderBy('w.'.$orderBy, $search->getOrder());
			}
		}
		
		if ($search->getFrom() && $search->getTo()) {
			$queryBuilder
				->andWhere($queryBuilder->expr()->between('w.beginAt', ':from', ':to'))
				->setParameter('from', $search->getFrom())
				->setParameter('to', $search->getTo())
			;
        }

        if ($search->getLastname()) {
			$queryBuilder
				->andWhere('u.lastname LIKE :lastname')
				->setParameter('lastname', $search->getLastname().'%');
        }
		
		if ($search->getState()) {
            $queryBuilder
				->addSelect('s')
				->join('w.state', 's')
                ->andWhere('s.slug = :slug')
                ->setParameter('slug', $search->getState());
		}

		if ($search->getIsUpdated() === false || $search->getIsUpdated()) {
            $queryBuilder
                ->andWhere('w.isUpdated = :isUpdated')
                ->setParameter('isUpdated', $search->getIsUpdated());
        }

        return $queryBuilder->getQuery();
	}
	
	public function findOldestSheets(User $user, \Datetime $limitDate)
	{
		return $this->_em->createQueryBuilder()
			->select('w')
			->from('App\Entity\Weeksheet', 'w')
			->andWhere('w.user = :user')
			->andWhere('w.beginAt < :limitdate')
			->setParameters(array(
				'user' => $user,
				'limitdate' => $limitDate
				))
			->getQuery()
			->getResult()
			;
	}
	// /**
	//  * @return Weeksheet[] Returns an array of Weeksheet objects
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
	public function findOneBySomeField($value): ?Weeksheet
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
