<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\DependencyStateProcessor;
use App\State\DependencyStateProvider;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints\Length;

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
        ),
        new Put(provider: DependencyStateProvider::class, processor: DependencyStateProcessor::class),
    ],
    paginationEnabled: false
)]
class Dependency
{
    #[ApiProperty(identifier: true)]
    private string $uuid;

    #[
        ApiProperty(description: 'Le nom de la dépendance'),
        Length(min: 2)
    ]
    private string $name;

    #[ApiProperty(description: 'La version de la dépendance')]
    private string $version;

    public function __construct($name, $version)
    {
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
        $this->name = $name;
        $this->version = $version;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name . ' ' . $this->version;
    }
}