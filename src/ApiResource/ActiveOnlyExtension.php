<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\PolymorphicEntity;
use Doctrine\ORM\QueryBuilder;

class ActiveOnlyExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    private array $notFiltered = [
      PolymorphicEntity::class
    ];

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass, $context);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass, $context);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass, array $context = []): void
    {

        if (in_array($resourceClass, $this->notFiltered)){
            return;
        }

        // Search if an "active" Filter is being requested, if not, apply a restriction to the QueryBuilder
        if (array_key_exists('filters', $context) && array_key_exists('active', $context['filters'])) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.active = :active', $rootAlias));
        $queryBuilder->setParameter('active', true);
    }
}