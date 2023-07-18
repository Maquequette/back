<?php

namespace App\Controller\Like;

use App\Entity\Like;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends AbstractController
{

    public static function like(
        int $id,
        ServiceEntityRepository $repository,
        Security $security,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $user = $security->getUser();
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
            $em->persist($like);

            $target->addLike($like);
            $em->persist($target);

            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public static function dislike(
        int $id,
        ServiceEntityRepository $repository,
        Security $security,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $user = $security->getUser();
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
            $em->persist($target);
            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}