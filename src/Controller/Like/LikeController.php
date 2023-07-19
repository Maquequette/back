<?php

namespace App\Controller\Like;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em
    ){ }

    public function like(int $id, ServiceEntityRepository $repository): JsonResponse
    {
        $user = $this->getUser();
        $target = $repository->find($id);

        $likes = $target->getLikes()->filter(
            function(Like $like) use($target, $user) {
                return $like->getUser()->getId() === $user->getId();
            }
        );

        if($likes->count() > 0){
            return new JsonResponse('', Response::HTTP_CONFLICT, [], true);
        }

        try {
            $like = new Like();
            $like->setUser($user);
            $like->setTarget($target);
            $this->em->persist($like);

            $target->addLike($like);
            $this->em->persist($target);

            $this->em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function dislike(int $id, ServiceEntityRepository $repository): JsonResponse
    {
        $user = $this->getUser();
        $target = $repository->find($id);

        $likes = $target->getLikes()->filter(
            function(Like $like) use($target, $user) {
                return $like->getUser()->getId() === $user->getId();
            }
        );

        if($likes->count() !== 1){
            return new JsonResponse('', Response::HTTP_CONFLICT, [], true);
        }

        try {
            $target->removeLike($likes->first());
            $this->em->persist($target);
            $this->em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}