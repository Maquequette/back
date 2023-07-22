<?php

namespace App\Controller\Comment;

use App\Controller\Like\LikeController;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentDislikeController extends AbstractController
{

    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly EntityManagerInterface $em,
        private readonly Security $security
    ){ }

    public function __invoke(Request $request, int $id): JsonResponse
    {
        return (new LikeController($this->em, $this->security))->dislike($id, $this->commentRepository);
    }
}