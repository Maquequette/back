<?php

namespace App\ApiResource;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Response;

class AuthOpenApiFactory implements OpenApiFactoryInterface
{

    public function __construct(private OpenApiFactoryInterface $decorated)
    {

    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $openApi
            ->getPaths()
            ->addPath('/auth/register', (new PathItem())->withPost(
                (new Operation())
                    ->withOperationId('register_post')
                    ->withTags(['Login Check'])
                    ->withResponses([
                        Response::HTTP_OK => [
                            'description' => 'User account is created',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'token' => [
                                                'readOnly' => true,
                                                'type' => 'string',
                                                'nullable' => false,
                                            ],
                                        ],
                                        'required' => ['token'],
                                    ],
                                ],
                            ],
                        ],
                    ])
                    ->withSummary('Creates a user account with tokens.')
                    ->withRequestBody(
                        (new RequestBody())
                            ->withDescription('The new user data')
                            ->withContent(new \ArrayObject([
                                'application/json' => new MediaType(new \ArrayObject(new \ArrayObject([
                                    'type' => 'object',
                                    'properties' => $properties = array_merge_recursive(
                                        $this->getJsonSchemaFromPathParts('firstname'),
                                        $this->getJsonSchemaFromPathParts('lastname'),
                                        $this->getJsonSchemaFromPathParts('email'),
                                        $this->getJsonSchemaFromPathParts('password'),
                                        $this->getJsonSchemaFromPathParts('confirm_password')
                                    ),
                                    'required' => array_keys($properties),
                                ]))),
                            ]))
                            ->withRequired(true)
                    )
            ));

        $openApi
            ->getPaths()
            ->addPath('/auth/logout', (new PathItem())->withPost(
                (new Operation())
                    ->withOperationId('logout_post')
                    ->withTags(['Login Check'])
                    ->withResponses([
                        Response::HTTP_OK => [
                            'description' => 'Refresh token is invalid',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'code' => [
                                                'readOnly' => true,
                                                'type' => 'int',
                                                'nullable' => false,
                                                'default' => 200
                                            ],
                                            'message' => [
                                                'type' => 'string',
                                                'default' => 'The supplied refresh_token has been invalidated.'
                                            ]
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ])
                    ->withSummary('Invalid refresh token.')
                    ->withRequestBody(
                        (new RequestBody())
                            ->withDescription('The refresh token to invalidate')
                            ->withContent(new \ArrayObject([
                                'multipart/form-data' => new MediaType(new \ArrayObject(new \ArrayObject([
                                    'type' => 'object',
                                    'properties' => $properties = array_merge_recursive(
                                        $this->getJsonSchemaFromPathParts('refresh_token')
                                    ),
                                    'required' => array_keys($properties),
                                ]))),
                            ]))
                            ->withRequired(true)
                    )
            ));

        $openApi
            ->getPaths()
            ->addPath('/auth/refresh', (new PathItem())->withPost(
                (new Operation())
                    ->withOperationId('refresh_post')
                    ->withTags(['Login Check'])
                    ->withResponses([
                        Response::HTTP_OK => [
                            'description' => 'user token is refresh',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'token' => [
                                                'readOnly' => true,
                                                'type' => 'string',
                                                'nullable' => false,
                                            ],
                                        ],
                                        'required' => ['token'],
                                    ],
                                ],
                            ],
                        ],
                    ])
                    ->withSummary('Recreates a user token.')
                    ->withRequestBody(
                        (new RequestBody())
                            ->withDescription('The refresh token to use')
                            ->withContent(new \ArrayObject([
                                'multipart/form-data' => new MediaType(new \ArrayObject(new \ArrayObject([
                                    'type' => 'object',
                                    'properties' => $properties = array_merge_recursive(
                                        $this->getJsonSchemaFromPathParts('refresh_token')
                                    ),
                                    'required' => array_keys($properties),
                                ]))),
                            ]))
                            ->withRequired(true)
                    )
            ));

        return $openApi;
    }

    private function getJsonSchemaFromPathParts(string $field): array
    {
        $jsonSchema = [];

        $jsonSchema[$field] = [
            'type' => 'string',
            'nullable' => false,
            'default' => $field
        ];

        return $jsonSchema;


    }
}