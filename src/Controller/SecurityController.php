<?php

namespace App\Controller;

use App\Entity\RefreshToken;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/auth')]
class SecurityController extends AbstractController
{

    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private readonly MailerService $mailer
    ){ }

    //<editor-fold desc="Register">
    #[Route(path: '/register', name: 'auth_register', methods: ['POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    ): JsonResponse {

        $user = new User();
        $serializer->deserialize(
            data: $request->getContent(),
            type: User::class,
            format: 'json',
            context: [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $em->persist($user);
        $em->flush();

        $user = $userRepository->findOneBy(['email' => $user->getEmail()]);

        $JWTtoken = $this->jwtManager->create($user);
        $refreshtoken = $this->refreshTokenGenerator->createForUserWithTtl($user, $this->getParameter('token.ttl'));

        $refresh = new RefreshToken();
        $refresh->setRefreshToken($refreshtoken);
        $refresh->setValid($refreshtoken->getValid());
        $refresh->setUsername($user->getId());

        $em->persist($refresh);
        $em->flush();

        $this->mailer->sendEmailToSomeone( $user->getEmail(), "Inscription confirmé", "Merci de votre inscription");

        return new JsonResponse([
            'token' => $JWTtoken,
            'refresh_token' => $refreshtoken->getRefreshToken(),
            'refresh_token_expiration' => $refreshtoken->getValid()->getTimestamp()
        ], Response::HTTP_OK);
    }
    //</editor-fold>

    //<editor-fold desc="Login">
    #[Route(path: '/login', name: 'auth_login', methods: ['POST'])]
    public function login(){

    }
    //</editor-fold>

    //<editor-fold desc="Logout">
    #[Route(path: '/logout', name: 'auth_logout', methods: ['POST'])]
    public function logout(){

    }
    //</editor-fold>

    //<editor-fold desc="Refresh">
    public function refresh(){

    }
    //</editor-fold>

}