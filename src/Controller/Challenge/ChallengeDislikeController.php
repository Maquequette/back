<?php

namespace App\Controller\Challenge;

use App\Entity\ChallengeLike;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChallengeDislikeController extends AbstractController
{

    private Security $security;
    private ChallengeRepository $challengeRepository;

    public function __construct(
        Security $security,
        ChallengeRepository $challengeRepository,
    ){
        $this->security = $security;
        $this->challengeRepository = $challengeRepository;
    }

    public function __invoke(Request $request, int $id, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->security->getUser();
        $challenge = $this->challengeRepository->find($id);

        $likes = $challenge->getChallengeLikes()->filter(
            function(ChallengeLike $challengeLike) use($challenge, $user) {
                return $challengeLike->getUser()->getId() === $user->getId();
            }
        );

        if($likes->count() !== 1){
            return new JsonResponse('', Response::HTTP_CONFLICT, [], true);
        }

        try {
            $challenge->removeChallengeLike($likes->first());
            $em->persist($challenge);
            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}