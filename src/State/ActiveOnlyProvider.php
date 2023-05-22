<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

class ActiveOnlyProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $itemProvider,
        private CollectionProvider $collectionProvider
    ) { }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $resourceClass = $operation->getClass();

        if ($operation instanceof CollectionOperationInterface) {
            $items = $this->collectionProvider->provide($operation, $uriVariables, $context);

            $filteredItems = [];
            foreach ($items as $item) {
                if ($item instanceof $resourceClass && $item->isActive()) {
                    $filteredItems[] = $item;
                }
            }

            return $filteredItems;
        }

        $item = $this->itemProvider->provide($operation, $uriVariables, $context);

        if ($item instanceof $resourceClass && !$item->isActive()){
            return null;
        }

        return $item;
    }
}
