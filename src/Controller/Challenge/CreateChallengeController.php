<?php

namespace App\Controller\Challenge;

use ApiPlatform\Exception\ItemNotFoundException;
use ApiPlatform\Validator\Exception\ValidationException;
use App\Controller\Resource\ResourceController;
use App\Entity\Challenge;
use App\Repository\ChallengeTypeRepository;
use App\Repository\DifficultyRepository;
use App\Repository\TagRepository;
use App\Service\AwsS3Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateChallengeController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly AwsS3Service $awsS3Service,
        private readonly DifficultyRepository $difficultyRepository,
        private readonly ChallengeTypeRepository $challengeTypeRepository,
        private readonly TagRepository $tagRepository,
        private readonly Security $security
    ){ }

    public function __invoke(RequestStack $requestStack, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $challenge = $this->validateChallenge($request->request->all());
            (new ResourceController($em, $this->awsS3Service, $this->security))->validateResources($request, $challenge);

            $em->persist($challenge);
            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    private function validateChallenge(array $inputs): Challenge
    {
        $user = $this->getUser();

        // Fetch input
        $inputs['title'] =  array_key_exists('title', $inputs) ? (string) $inputs['title'] : null;
        $inputs['description'] = array_key_exists('description', $inputs) ? (string) $inputs['description'] : null;
        $inputs['difficulty'] = array_key_exists('difficulty', $inputs) ? (int) $inputs['difficulty'] : null;
        $inputs['type'] = array_key_exists('type', $inputs) ? (int) $inputs['type'] : null;

        // Check mandatory fields
        if( null === $inputs['title'] | null === $inputs['description'] | null === $inputs['difficulty'] | null === $inputs['type']){
            throw new ItemNotFoundException('mandatory fields');
        }

        $challenge = new Challenge();
        $challenge
            ->setAuthor($user)
            ->setTitle( (string) $inputs['title'])
            ->setDescription( (string) $inputs['description'])
        ;

        // Retrieve Difficulty
        if ((int) $inputs['difficulty']){
            $difficulty = $this->difficultyRepository->find($inputs['difficulty']);
            if (!$difficulty){
                throw new ItemNotFoundException('Item not found for Difficulty '.$inputs['difficulty']);
            }
            $challenge->setDifficulty($difficulty);
        }

        // Retrieve ChallengeType
        if ((int) $inputs['type']){
            $type = $this->challengeTypeRepository->find($inputs['type']);
            if (!$type){
                throw new ItemNotFoundException('Item not found for ChallengeType '.$inputs['type']);
            }
            $challenge->setType($type);
        }

        // Retrieve Tags
        if(array_key_exists('tags', $inputs) && is_array($inputs['tags'])){
            foreach ($inputs['tags'] as $tag_id){
                $tag = $this->tagRepository->find($tag_id);
                if (!$tag){
                    throw new ItemNotFoundException('Item not found for Tag '.$tag_id);
                }
                $challenge->addTag($tag);
            }
        }

        // Validate Challenge
        $errors = $this->validator->validate($challenge);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidationException($errorsString);
        }

        return $challenge;
    }
}