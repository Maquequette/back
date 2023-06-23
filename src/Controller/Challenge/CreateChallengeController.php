<?php

namespace App\Controller\Challenge;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Entity\Challenge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateChallengeController extends AbstractController
{

    private Security $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    public function __invoke(RequestStack $requestStack, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        try {
            $challenge = $this->validateMultipart($request);

        } catch (ValidationException $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $challenge->setAuthor($user);

        $errors = $validator->validate($challenge);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST);
        }

        dd($challenge);

        return new JsonResponse('', Response::HTTP_OK, [], true);
    }

    protected function validateMultipart(Request $request): Challenge {

        $inputs = $request->request->all();

        dd($inputs);

        if(
            null === $inputs['title'] |
            null === $inputs['description'] |
            null === $inputs['difficulty'] |
            null === $inputs['type']
        ){

        }

        return new Challenge();
    }
}