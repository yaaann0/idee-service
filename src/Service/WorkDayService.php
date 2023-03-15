<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Weeksheet;
use App\Entity\WorkDay;
use App\Repository\WorkDayRepository;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;

class WorkDayService
{
    private $repository;

    private $em;
    
    private $taskService;

	public function __construct(WorkDayRepository $repository, EntityManagerInterface $em, TaskService $taskService) 
	{
		$this->repository = $repository;
        $this->em = $em;
        $this->taskService = $taskService;
    }
    
    public function initNewWeek(Weeksheet $week, User $user)
    {
        $currentDay = new \DateTime();
        $currentDay->setTimestamp($week->getBeginAt()->getTimestamp());
        
        while ($currentDay <= $week->getFinishAt()) {
            $day = new WorkDay();
            $datetime = new \DateTime();
            $datetime->setTimestamp($currentDay->getTimestamp());
            $day
                ->setUser($user)
                ->setWeeksheet($week)
                ->setDatetime($datetime)
            ;
            $this->em->persist($day);
            $currentDay->add(new DateInterval('P1D'));
        }
        $this->em->flush();
    
    }

    public function getDaysDurations(Weeksheet $week)
    {
        $daysDurations = array('week' => 0);

        foreach ($week->getWorkDays() as $day) {
            $duration = $this->taskService->getDayDuration($day);
            $daysDurations[$day->getId()] = $duration;
            $daysDurations['week'] += $duration;
        }
        return $daysDurations;
    }

}
