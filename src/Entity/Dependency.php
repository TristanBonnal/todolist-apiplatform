<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\State\DependencyStateProcessor;
use App\State\DependencyStateProvider;
use Ramsey\Uuid\Uuid;

#[ApiResource(
    operations: [
        new Get(provider: DependencyStateProvider::class),
        new GetCollection(provider: DependencyStateProvider::class),
        new Post(
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'description' => 'Le nom de la dépendance'
                                    ],
                                    'version' => [
                                        'type' => 'string',
                                        'description' => 'La version de la dépendance'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            processor: DependencyStateProcessor::class
        )
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

    public function __construct($name, $version)
    {
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
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