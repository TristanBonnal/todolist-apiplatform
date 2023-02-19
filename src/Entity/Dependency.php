<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\DependencyStateProvider;

#[ApiResource(
    operations: [
        new Get(provider: DependencyStateProvider::class),
        new GetCollection(provider: DependencyStateProvider::class)
    ],
    paginationEnabled: false
)]
class Dependency
{
    #[ApiProperty(identifier: true)]
    private $uuid;

    #[ApiProperty(description: 'Le nom de la dépendance')]
    private $name;

    #[ApiProperty(description: 'La version de la dépendance')]
    private $version;

    public function __construct($uuid, $name, $version)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->version = $version;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function __toString()
    {
        return $this->name . ' ' . $this->version;
    }
}