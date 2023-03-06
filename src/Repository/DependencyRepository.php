<?php

namespace App\Repository;

use App\Entity\Dependency;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\KernelInterface;

class DependencyRepository
{
    private string $rootPath;

    public function __construct(ManagerRegistry $registry, private readonly KernelInterface $appKernel)
    {
        $this->rootPath = $this->appKernel->getProjectDir() . '/composer.json';
    }

    /**
     * @return Dependency[]
     */
    public function findAll(): array
    {
        // Récupération des dépendances du projet dans le fichier composer.json
        $composerEntries = json_decode(file_get_contents($this->rootPath), true);
        $dependencies = [];

        // On crée un objet Dependency pour chaque dépendance
        foreach ($composerEntries['require'] as $dependency => $version) {
            $dependencies[] = new Dependency($dependency, $version);
        }
        return $dependencies;
    }

    public function find(string $uuid): ?Dependency
    {
        $dependencies = $this->findAll();

        // On retourne la dépendance demandée s'il existe
        foreach ($dependencies as $dependency) {
            if ($dependency->getUuid() === $uuid) {
                return $dependency;
            }
        }

        return null;
    }

    public function persist(Dependency $dependency): void
    {
        $composerEntries = json_decode(file_get_contents($this->rootPath), true);
        $composerEntries['require'][$dependency->getName()] = $dependency->getVersion();
        file_put_contents($this->rootPath, json_encode($composerEntries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
