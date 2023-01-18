<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    public function __invoke(Task $task) : Task
    {
        return $task;
//        return $this->json(
//            data:$taskRepo->findAll(),
//            status: 209,
//            context: ['groups' => ['read:collection']]
//        );
    }
}
