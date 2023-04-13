<?php
namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new \ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT',
        ]);

        $openApi->getPaths()->addPath('/api/login', self::getLoginPath());

        return $openApi;
    }

    private static function getLoginPath(): PathItem
    {
        $operation = (new Operation())
            ->withTags(["Login"])    // Nom du groupe
            ->withSummary("Authentification")
            ->withDescription("Authentification par cookie")
            ->withRequestBody(new RequestBody(
                'Fournir le mail et le mot de passe',
                new \ArrayObject([
                    'application/ld+json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'username' => ['type' => 'string'],
                                'password' => ['type' => 'string'],
                            ],
                        ],
                        'example' => [
                            'username' => "tbonnal@qerys.com",
                            'password' => "1234",
                        ],
                    ]
                ])
            ))
            ->withResponses([
                '200' => [
                    'description' => 'OK',
                    'content' => [
                        'application/ld+json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/User-read.collection.User',
                            ]
                        ]
                    ]
                ],
                '401' => [
                    'description' => 'Unauthorized',
                ],
            ])
    ;

        return (new PathItem())->withPost($operation);
    }
}