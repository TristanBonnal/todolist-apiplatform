<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\TaskRepository;
use Symfony\Bundle\SecurityBundle\Security;


class TaskStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly TaskRepository $taskRepository, private readonly Security $security)
    {
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // Pour gérer PUT ou DELETE, on pourrait vérifier l'opération et agir en conséquence
        $task = $data;
        $task->setUser($this->security->getUser());

        $this->taskRepository->save($task,true);
    }
}
