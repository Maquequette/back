<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Like;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

class ChallengeExtension implements QueryCollectionExtensionInterface
{
    public function __construct(private readonly Security $security){ }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {

        if ($operation->getName() === 'LikedChallenges'){
            $this->likedChallenges($queryBuilder, $resourceClass, $context);
        }

        if ($operation->getName() === 'MyChallenges'){
            $this->myChallenges($queryBuilder, $resourceClass, $context);
        }

    }

    private function likedChallenges(QueryBuilder $queryBuilder, string $resourceClass, array $context = []): void
    {

        if (null === $user = $this->security->getUser()){
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->innerJoin(
            join: Like::class,
            alias: 'l',
            conditionType: Expr\Join::WITH,
            condition: sprintf('%s.id = l.target', $rootAlias)
        );
        $queryBuilder->andWhere('l.user = :current_user');
        $queryBuilder->setParameter('current_user', $user->getId());
    }

    private function myChallenges(QueryBuilder $queryBuilder, string $resourceClass, array $context = []): void
    {
        if (null === $user = $this->security->getUser()){
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.author = :current_user', $rootAlias));
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}