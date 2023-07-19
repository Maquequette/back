<?php

namespace App\Controller\Challenge;

use App\Controller\Like\LikeController;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChallengeLikeController extends AbstractController
{

    public function __construct(
        private readonly ChallengeRepository $challengeRepository,
        private readonly EntityManagerInterface $em
    ){ }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        return (new LikeController($this->em))->like($id, $this->challengeRepository);
    }
}