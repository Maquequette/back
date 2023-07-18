<?php

namespace App\Controller\Challenge;

use App\Controller\Like\LikeController;
use App\Repository\ChallengeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChallengeLikeController extends AbstractController
{

    private ChallengeRepository $challengeRepository;
    private Security $security;
    private EntityManagerInterface $em;

    public function __construct(
        ChallengeRepository $challengeRepository,
        Security $security,
        EntityManagerInterface $em
    ){
        $this->challengeRepository = $challengeRepository;
        $this->security = $security;
        $this->em = $em;
    }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        return LikeController::like($id, $this->challengeRepository, $this->security, $this->em);
    }
}