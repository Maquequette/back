<?php

namespace App\Filter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Exception\InvalidArgumentException;
use ApiPlatform\Metadata\Operation;
use App\Entity\Like;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class OrderByLikesCount extends AbstractFilter
{

    private string $searchParameterName;

    /**
     * Add configuration parameter
     * {@inheritdoc}
     * @param string $searchParameterName The parameter whose value this filter searches for
     */
    public function __construct(ManagerRegistry $managerRegistry, LoggerInterface $logger = null, array $properties = null, NameConverterInterface $nameConverter = null, string $searchParameterName = 'search')
    {
        parent::__construct($managerRegistry, $logger, $properties, $nameConverter);

        $this->searchParameterName = $searchParameterName;
    }

    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {

        if (is_array($value) && array_key_exists('likesCount', $value)) {
            $queryBuilder
                ->addSelect('COUNT(l.target) as likesCount')
                ->leftJoin(Like::class, "l", "WITH", "l.target = o.id")
                ->groupBy('o.id')
                ->orderBy("likesCount", "DESC");
        }
    }

    public function getDescription(string $resourceClass): array
    {

        return [
            $this->searchParameterName => [
                'property' => "order[likesCount]",
                'type' => 'string',
                'required' => false,
                'swagger' => [
                    'description' => 'Order by likesCount',
                ]
            ]
        ];
    }
}