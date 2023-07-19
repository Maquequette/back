<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class MeController extends AbstractController
{

    public function __invoke(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('User');

        $data = $serializer->serialize(
            data: $user,
            format: 'json',
            context: $context->toArray()
        );

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }
}