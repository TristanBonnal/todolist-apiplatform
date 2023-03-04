<?php

namespace App\State;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\KernelInterface;

class DependencyStateProvider implements ProviderInterface
{

    public function __construct(private readonly KernelInterface $appKernel)
    {
    }


    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Récupération des dépendances du projet dans le fichier composer.json
        $rootPath = $this->appKernel->getProjectDir();
        $composerEntries = json_decode(file_get_contents($rootPath . '/composer.json'), true);
        $dependencies = [];

        // On crée un objet Dependency pour chaque dépendance
        foreach ($composerEntries['require'] as $dependency => $version) {
            $dependencies[] = new Dependency(
                Uuid::uuid5(Uuid::NAMESPACE_URL, $dependency)->toString(),
                $dependency,
                $version
            );
        }
        // Si on est sur une opération de collection, on retourne tout
        if ($operation instanceof CollectionOperationInterface) {
            return [$dependencies];
        }

        // Sinon, on retourne l'élément demandé s'il existe
        foreach ($dependencies as $dependency) {
            if ($dependency->getUuid() === $uriVariables['uuid']) {
                return $dependency;
            }
        }

        return null;
    }
}
