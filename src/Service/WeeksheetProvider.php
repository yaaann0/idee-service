<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Weeksheet;
use App\Repository\SheetStateRepository;
use App\Repository\WeeksheetRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class WeeksheetProvider
{
	private $repository;	
	private $em;
	private $workDayService;
	private $intervalBefore;
	private $intervalAfter;
	private $sheetsMonthLifespan;
	private $sheetStateRepository;

	public function __construct(WeeksheetRepository $repository, EntityManagerInterface $em, WorkDayService $workDayService, $intervalBefore, $intervalAfter, $sheetsMonthLifespan, SheetStateRepository $sheetStateRepository) 
	{
		$this->repository = $repository;
		$this->em = $em;
		$this->workDayService = $workDayService;
		$this->intervalBefore = $intervalBefore;
		$this->intervalAfter = $intervalAfter;
		$this->sheetsMonthLifespan = $sheetsMonthLifespan;
		$this->sheetStateRepository = $sheetStateRepository;
	}

	public function getAddable()
	{
		$addableWeeks = [];

		$intervalBefore = new \DateInterval('P'.$this->intervalBefore.'W');
		$intervalAfter = new \DateInterval('P'.$this->intervalAfter.'W');

		$limit = new DateTime();
		$limit->add($intervalAfter);

		$week = new DateTime();
		$week
			->sub($intervalBefore)
			->setTime(0,0,0,0);

		$correction = min($week->format('w'), $week->format('d'));
		if ($correction > 0) {
			$correction-=1;
		}
		$week->sub(new \DateInterval('P'.$correction.'D'));

		while ($week <= $limit) {
			$weeksheet = new Weeksheet();
			$lastday = $this->getLastday($week);
			$weeksheet
				->setUser(null)
				->setBeginAt($week)
				->setFinishAt($lastday)
				->setState($this->sheetStateRepository->findOneBy(['slug'=>'draft']))
				->setIsUpdated(false)
			;			
			$key = $week->format('W - j M Y');
			$addableWeeks[$key] = $weeksheet;
			$week = $this->getFirstDay($lastday);
		}

		return $addableWeeks;
	}

	public function provide(User $user)
	{
		$today = new DateTime();
		$today->sub(new DateInterval('P1D'))->setTime(0,0,0,0);
		$lastFinishAt = $this->defineLastFinishAt($user, $today);

		$this->removeOldSheets($user);

		while ($lastFinishAt <= $today) {
			$lastFinishAt = $this->createWeeksheet($lastFinishAt, $user);
		}

		$this->em->flush();

		return $this->repository->findByUserQuery($user, 'beginAt', 'DESC');
	}

	public function manualAdd(Weeksheet $weeksheet, User $user)
	{
		$weeksheet->setUser($user);
		
		$existingSheet = $this->repository->findExistingSheet($weeksheet->getUser(), $weeksheet->getBeginAt());

		if (!$existingSheet) {
			$this->em->persist($weeksheet);
			$this->em->flush();

			$this->workDayService->initNewWeek($weeksheet, $user);

			return $weeksheet;
		}

		return $existingSheet;
	}

	private function defineLastFinishAt(User $user, DateTime $today)
	{
		$lastSheet = $this->repository->findBy(['user' => $user], ['beginAt' => 'DESC'], 1);

		if (!$lastSheet) {		
			$prevWeekEnd = new DateTime();
			$prevWeekEnd->setTime(0,0,0,0);
			$weekDay = intval($today->format('w')) + 1;
			$monthDay = intval($today->format('j'));

			return $monthDay <= 6 ? $prevWeekEnd->sub(new DateInterval('P'.$monthDay.'D')) : $prevWeekEnd->sub(new DateInterval('P'.$weekDay.'D'));
		}
		
		return $lastSheet[0]->getFinishAt();
	}

	private function createWeeksheet(DateTime $prevLastDay, User $user)
	{	
		$beginAt = $this->getFirstDay($prevLastDay);
		$finishAt = $this->getLastday($beginAt);
		
		$weeksheet = new Weeksheet();
		$weeksheet
			->setUser($user)
			->setBeginAt($beginAt)
			->setFinishAt($finishAt)
			->setState($this->sheetStateRepository->findOneBy(['slug'=>'draft']))
			->setIsUpdated(false)
		;
		$this->em->persist($weeksheet);
		
		$this->workDayService->initNewWeek($weeksheet, $user);
		
	 	return $finishAt;
	}

	private function getFirstDay(DateTime $prevLastDay)
	{
		$firstday = new DateTime();
		$firstday->setTimestamp($prevLastDay->getTimestamp());

		switch (intval($prevLastDay->format('w'))) {
			case 6:
				return $firstday->add(new DateInterval('P2D'));
				break;
			
			default:
				return $firstday->add(new DateInterval('P1D'));
				break;
		}
	}

	private function getLastDay(DateTime $firstday)
	{
		$oneday = new DateInterval('P1D');
		$fiveday = new DateInterval('P5D');
		$lastday = new DateTime();
		$lastday->setTimestamp($firstday->getTimestamp());
		$i = new DateTime();
		$i->setTimestamp($firstday->getTimestamp());

		for ($i; $i < $lastday->add($fiveday); $i->add($oneday)) { 

			if (intval($i->format('n')) != intval($firstday->format('n'))) {
				return $i->sub($oneday);
			}

			if (intval($i->format('w')) === 6) {
				return $i;
			}
		}

		return $lastday;
	}

	public function removeOldSheets(User $user)
	{
		$limitDate = (new DateTime())->sub(new DateInterval('P'.$this->sheetsMonthLifespan.'M'));

		$oldSheets = $this->repository->findOldestSheets($user, $limitDate);

		foreach ($oldSheets as $oldSheet) {
			$this->em->remove($oldSheet);
		}
	}
}
