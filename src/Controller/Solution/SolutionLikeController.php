<?php

namespace App\Controller\Solution;

use App\Controller\Like\LikeController;
use App\Repository\CommentRepository;
use App\Repository\SolutionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SolutionLikeController extends AbstractController
{

    public function __construct(
        private readonly SolutionRepository $solutionRepository,
        private readonly EntityManagerInterface $em,
        private readonly Security $security
    ){ }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        return (new LikeController($this->em, $this->security))->like($id, $this->solutionRepository);
    }
}