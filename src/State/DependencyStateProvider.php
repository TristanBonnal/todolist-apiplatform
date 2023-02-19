<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Dependency;

class DependencyStateProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            return [new BlogPost(), new BlogPost()];
        }

        return [new Dependency('12315', 'test', '1.0.0'), new Dependency('12315', 'test', '1.0.0')];
    }
}
