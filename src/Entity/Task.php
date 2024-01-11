<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\TestController;
use App\Repository\TaskRepository;
use App\State\TaskStateProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [       // Comportement spécifique à chaque opération
        new Get(normalizationContext: ['groups' => ['read:item']]),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(denormalizationContext: ['groups' => ['read:item']]),
        new Get(
            uriTemplate: '/custom/{id}',
            controller: TestController::class,
            openapiContext: [
                'summary' => 'Récupère une task custom',
                'description' => 'Une description de la requête de qualité'
            ],
            paginationEnabled: false,
            filters: []
        ),
    ],
    normalizationContext: [     // Données récupérées lors de la réponse
        'groups' => ['read:collection'],
        'openapi_definition_name' => 'Collection'
    ],
    denormalizationContext: [       // Données persistées lors de la requête
        'groups' => ['write:collection'],
        'openapi_definition_name' => 'Collection-write'
    ],
    validationContext: ['groups' => ['write:collection']],
    paginationItemsPerPage: 5,

)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'content' => 'partial'])]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:collection','read:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 2000)]
    #[Groups(['read:collection', 'write:collection','read:item'])]
    #[Length(min: 8, groups: ['write:collection'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['write:collection','read:item', 'read:collection'])]
    #[ApiProperty(openapiContext: ['description' => 'Tâche en cours (0) ou complétée (1)'])]
    private ?bool $status = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'tasks')]
    #[Groups(['write:collection','read:item', 'read:collection'])]
    #[Valid()]
    private ?Category $category = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['write:collection','read:item', 'read:collection'])]
    private ?\DateTimeInterface $createdAt = null;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
