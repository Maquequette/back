<?php

namespace App\State;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Challenge;
use App\Entity\ChallengeLike;
use Symfony\Bundle\SecurityBundle\Security;

class ChallengeIsLikedProvider implements ProviderInterface
{

    public function __construct(
        private readonly ProviderInterface $itemProvider,
        private readonly CollectionProvider $collectionProvider,
        private readonly Security $security
    )
    { }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        $data = null;

        if ($operation instanceof CollectionOperationInterface) {
            $data = $this->collectionProvider->provide($operation, $uriVariables, $context);
        } else {
            $data = $this->itemProvider->provide($operation, $uriVariables, $context);
        }

        $user = $this->security->getUser();
        if(!$user){
            return $data;
        }

        if ($operation instanceof CollectionOperationInterface) {

            foreach ($data as $challenge){
                $challenge->getChallengeLikes()->filter(
                    function(ChallengeLike $challengeLike) use($challenge, $user) {
                        if($challengeLike->getUser()->getId() === $user->getId()){
                            $challenge->setIsLiked(true);
                        }
                    }
                );
            }

        } else {
            $data->getChallengeLikes()->filter(
                function(ChallengeLike $challengeLike) use($data, $user) {
                    if($challengeLike->getUser()->getId() === $user->getId()){
                        $data->setIsLiked(true);
                    }
                }
            );
        }

        return $data;
    }
}
