<?php

namespace App\Service;

use App\Entity\Developer;
use App\Entity\Task;
use App\Repository\DeveloperRepository;
use App\Repository\TaskRepository;

class TaskAllocator
{
    private DeveloperRepository $developerRepository;
    private TaskRepository $taskRepository;

    public function __construct(DeveloperRepository $developerRepository, TaskRepository $taskRepository)
    {
        $this->developerRepository = $developerRepository;
        $this->taskRepository = $taskRepository;
    }

    public function allocateTasks(): array
    {

        /**
         * @var Task[] $tasks
         */
        $tasks = $this->taskRepository->findBy([], ['difficulty' => 'DESC']);

        /**
         * @var Developer[] $developers
         */
        $developers = $this->developerRepository->findBy([], ['capacity' => 'DESC']);


        /**
         * @var array $schedule
         * Weekly schedule
         */
        $schedule = [];

        /**
         * @var int $week
         * Week counter
         */
        $week = 1;

        /**
         * @var Task[] $remainingTasks
         * Remaining tasks to be assigned
         */
        $remainingTasks = $tasks;

        while (!empty($remainingTasks)) {
            $weeklyAssignment = [];

            foreach ($developers as $developer) {
                $assignedTasks = [];
                $weeklyLoad = 0;

                foreach ($remainingTasks as $key => $task) {
                    $taskDuration = $task->getComplexity() / $developer->getCapacity(); // Görevin süresi

                    if ($weeklyLoad + $taskDuration <= 45 && $task->getDifficulty() >= $developer->getCapacity()) {
                        $assignedTasks[] = $task;
                        $weeklyLoad += $taskDuration;
                        unset($remainingTasks[$key]); // Atanan görevi listeden kaldır
                    }
                }

                $weeklyAssignment[$developer->getName()] = $assignedTasks;
            }

            $schedule["Week $week"] = $weeklyAssignment;
            $week++;
        }

        return ['schedule' => $schedule, 'total_weeks' => $week - 1];
    }
}
