<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Weeksheet;
use App\Entity\WorkDay;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;

class TaskService
{
    private $repository;
	private $em;

	public function __construct(TaskRepository $repository, EntityManagerInterface $em) 
	{
        $this->repository = $repository;
		$this->em = $em;
    }
    
    public function addEmptyTask(Weeksheet $weeksheet)
    {
        foreach ($weeksheet->getWorkDays() as $day) {
            $task = new Task();
			$task
				->setBeginAt($day->getDatetime())
				->setEndAt($day->getDatetime())
				->setClientName('')
                ->setIsUpdated(false)
                ->setWorkDay($day)
			;
            $day->addTask($task);
        }
    }

    public function submitTasks(Weeksheet $weeksheet, User $user)
    {
        $days = $weeksheet->getWorkDays();
        $isAdmin = in_array('ROLE_ADMIN', $user->getRoles());
        $date = new \DateTime();
        $today = $date->format('d-m-y');

        foreach ($days as $day) {
            foreach ($day->getTasks() as $task) {

                if (!$task->getClientName()) {
                    $day->removeTask($task);
                    continue 1;
                }

                if (!$isAdmin) {  
                    $task->setUpdateDescription(null);
                    $task->setIsUpdated($isAdmin);
                    continue 1;
                }

                if (!$task->getId()) {
                    $task->setUpdateDescription(' Ajoutée');
                    $task->setIsUpdated($isAdmin);
                    continue 1;
                } 

                $newTask = clone $task;
                $this->em->refresh($task);

                if ($newTask->getClientName() != $task->getClientName()) {
                    $task->setUpdateDescription(' Changement nom client;');
                    $task->setIsUpdated($isAdmin);
                }

                if ($newTask->getBeginAt() != $task->getBeginAt()) {
                    $task->setUpdateDescription(' Début '.$task->getBeginAt()->format('H:i').' -> '.$newTask->getBeginAt()->format('H:i').';');
                    $task->setIsUpdated($isAdmin);
                }

                if ($newTask->getEndAt() != $task->getEndAt()) {
                    $task->setUpdateDescription(' Fin '.$task->getEndAt()->format('H:i').' -> '.$newTask->getEndAt()->format('H:i').';');
                    $task->setIsUpdated($isAdmin);
                }

                $task
                    ->setClientname($newTask->getClientname())
                    ->setBeginAt($newTask->getBeginAt())
                    ->setEndAt($newTask->getEndAt())
                ;
            }
        }
        $weeksheet->setIsUpdated($isAdmin);
    }

    public function getDayDuration(WorkDay $day)
    {
        $dayDuration = 0;

        foreach ($day->getTasks() as $task) {
            $taskDuration = $task->getEndAt()->getTimeStamp() - $task->getBeginAt()->getTimeStamp();
            $dayDuration += ($taskDuration / 3600);
        }

        return $dayDuration;
    }

    public function copyTasks(WorkDay $originDay, WorkDay $targetDay)
    {
        $originTasks = $this->repository->findBy(['workDay' => $originDay]);
        $targetTasks = $this->repository->findBy(['workDay' => $targetDay]);

        foreach ($targetTasks as $task ) {
            $targetDay->removeTask($task);
        }

        foreach ($originTasks as $task) {
            $copy = new Task();
            $copy
                ->setClientName($task->getClientName())
                ->setBeginAt($task->getBeginAt())
                ->setEndAt($task->getEndAt())
                ->setIsUpdated(false)
                ->setWorkDay($targetDay);

            $this->em->persist($copy);
        }

        $this->em->flush();
    }

}
