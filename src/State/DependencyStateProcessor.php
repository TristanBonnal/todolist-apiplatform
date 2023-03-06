<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Dependency;
use App\Repository\DependencyRepository;

class DependencyStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly DependencyRepository $dependencyRepository)
    {
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // Pour gérer PUT ou DELETE, on pourrait vérifier l'opération et agir en conséquence

        $this->dependencyRepository->persist(
            new Dependency(
                $data->getName(),
                $data->getVersion()
            )
        );
    }
}
