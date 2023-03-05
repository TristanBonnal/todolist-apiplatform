<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\DependencyRepository;

class DependencyStateProvider implements ProviderInterface
{
    public function __construct(private readonly DependencyRepository $dependencyRepository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Si on est sur une opération de collection, on retourne toutes les dépendances
        if ($operation instanceof CollectionOperationInterface) {
            return $this->dependencyRepository->findAll();
        }

        // Sinon, on retourne la dépendance demandée
        return $this->dependencyRepository->find($uriVariables['uuid']);
    }
}
