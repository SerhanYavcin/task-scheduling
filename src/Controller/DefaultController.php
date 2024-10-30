<?php

namespace App\Controller;

use App\Service\TaskAllocator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{


    /**
     * @Route("/default", name="app_default")
     */
    public function index(TaskAllocator $taskAllocator): Response
    {
        $weeklyPlan = $taskAllocator->allocateTasks();


        return $this->render('default/index.html.twig', [
            'schedule' => $weeklyPlan['schedule'],
            'totalWeeks' => $weeklyPlan['total_weeks'],
        ]);
    }
}
