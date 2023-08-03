<?php

namespace App\Controller\Solution;

use ApiPlatform\Exception\ItemNotFoundException;
use ApiPlatform\Validator\Exception\ValidationException;
use App\Controller\Resource\ResourceController;
use App\Entity\Resource;
use App\Entity\Solution;
use App\Repository\ChallengeRepository;
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

class CreateSolutionController extends AbstractController
{

    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly AwsS3Service $awsS3Service,
        private readonly Security $security,
        private readonly ChallengeRepository $challengeRepository
    ){ }

    public function __invoke(RequestStack $requestStack, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $solution = $this->validateSolution($request->request->all());
            (new ResourceController($em, $this->awsS3Service, $this->security))->validateResources($request, $solution, [Resource::TYPE_IMAGE]);

            $em->persist($solution);
            $em->flush();

            return new JsonResponse('', Response::HTTP_OK, [], true);

        } catch (\Exception $exception){
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    protected function validateSolution(array $inputs): Solution
    {
        $user = $this->getUser();

        // Fetch input
        $inputs['challenge'] = array_key_exists('challenge', $inputs) ? (string) $inputs['challenge'] : null;
        $inputs['visible'] = array_key_exists('visible', $inputs) ? (string) $inputs['visible'] : null;
        $inputs['recap'] = array_key_exists('recap', $inputs) ? (string) $inputs['recap'] : null;

        // Check mandatory fields
        if( null === $inputs['challenge'] | null === $inputs['visible'] ){
            throw new ItemNotFoundException('mandatory fields');
        }

        $solution = new Solution();
        $solution
            ->setAuthor($user)
            ->setRecap($inputs['recap'])
            ->setVisible($inputs['visible'])
        ;

        // Retrieve Challenge
        if ((int) $inputs['challenge']){
            $challenge = $this->challengeRepository->find($inputs['challenge']);
            if (!$challenge){
                throw new ItemNotFoundException('Item not found for Challenge '.$inputs['challenge']);
            }
            $solution->setChallenge($challenge);
        }

        // Validate Solution
        $errors = $this->validator->validate($solution);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new ValidationException($errorsString);
        }

        return $solution;
    }

}