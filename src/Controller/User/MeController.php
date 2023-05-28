<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class MeController extends AbstractController
{

    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public function __invoke(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->security->getUser();

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