<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Comment;
use Doctrine\ORM\QueryBuilder;

class GetEntityCommentExtension implements QueryCollectionExtensionInterface
{

    private array $filtered = [
        Comment::class
    ];

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        //dump($context);
        $this->verification($resourceClass, $context);
    }

    private function verification(string $resourceClass, array $context = []): void
    {
        if (!in_array($resourceClass, $this->filtered)){
            return;
        }

        if (!array_key_exists('filters', $context)) {
            throw new \Exception('Commentable entity identifiant is mandatory.');

        } else if(!array_key_exists('parent.id', $context['filters'])){
            throw new \Exception('Commentable entity identifiant is mandatory.');
        }
    }
}