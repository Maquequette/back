<?php

namespace App\Controller\Challenge;

use ApiPlatform\Exception\ItemNotFoundException;
use ApiPlatform\Validator\Exception\ValidationException;
use App\Entity\Challenge;
use App\Entity\Ressource;
use App\Entity\User;
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

    private Security $security;
    private ValidatorInterface $validator;
    private AwsS3Service $awsS3Service;
    private DifficultyRepository $difficultyRepository;
    private ChallengeTypeRepository $challengeTypeRepository;
    private TagRepository $tagRepository;

    public function __construct(
        Security $security,
        ValidatorInterface $validator,
        AwsS3Service $awsS3Service,
        DifficultyRepository $difficultyRepository,
        ChallengeTypeRepository $challengeTypeRepository,
        TagRepository $tagRepository,
    ){
        $this->security = $security;
        $this->validator = $validator;
        $this->awsS3Service = $awsS3Service;
        $this->difficultyRepository = $difficultyRepository;
        $this->challengeTypeRepository = $challengeTypeRepository;
        $this->tagRepository = $tagRepository;
    }

    public function __invoke(RequestStack $requestStack, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $challenge = $this->validateChallenge($request->request->all());
            $resources = $this->validateResources($request, $challenge, $em);

            $em->persist($challenge);
            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    protected function validateChallenge(array $inputs): Challenge {
        //dd($inputs);
        $user = $this->security->getUser();

        // Fetch input
        $inputs['title'] =  array_key_exists('title', $inputs) ? (string) $inputs['title'] : null;
        $inputs['description'] = array_key_exists('description', $inputs) ? (string) $inputs['description'] : null;
        $inputs['difficulty'] = array_key_exists('difficulty', $inputs) ? (int) $inputs['difficulty'] : null;
        $inputs['type'] = array_key_exists('type', $inputs) ? (int) $inputs['type'] : null;

        // Check mandatory fields
        if( null === $inputs['title'] | null === $inputs['description'] | null === $inputs['difficulty'] | null === $inputs['type']){
            throw new ItemNotFoundException('mandatory fields');
        }

        $challenge  = new Challenge();
        $challenge->setAuthor($user);
        $challenge->setTitle( (string) $inputs['title']);
        $challenge->setDescription( (string) $inputs['description']);

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

    protected function validateResources(Request $request, Challenge $challenge, EntityManagerInterface $em): array {
        $user = $this->security->getUser();
        $inputs = $request->request->all();
        $files = $request->files->all();

        // Retrieve and Create Resources
        $items = [];
        if (array_key_exists('resources', $inputs) && is_array($inputs['resources'])){
            foreach ($inputs['resources'] as $i => $resource){
                //dd($ressource);

                $resource['label'] =  array_key_exists('label', $resource) ? (string) $resource['label'] : null;
                $resource['type'] =  array_key_exists('type', $resource) ? (string) $resource['type'] : null;

                if( null === $resource['type']){
                    throw new ItemNotFoundException('mandatory fields');
                }

                // Upload to AWS
                $path = './'.$user->getEmail().'/challenges';
                $filepath = $this->awsS3Service->uploadFile($path, $files['resources'][$i]['value']);

                // create a Ressource
                $item = new Ressource();
                $item->setLabel($resource['label']);
                $item->setType($resource['type']);
                $item->setValue($filepath);
                $item->setChallenge($challenge);
                $items[] = $item;

                $em->persist($item);
            }
        }

        return $items;
    }
}