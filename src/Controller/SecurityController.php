<?php

namespace App\Controller;

use App\Entity\User;
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

    private JWTTokenManagerInterface $jwtManager;

    //private RefreshTokenGeneratorInterface $refreshTokenGenerator;

    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        //RefreshTokenGeneratorInterface $refreshTokenGenerator
    ){
        $this->jwtManager = $jwtManager;
        //$this->refreshTokenGenerator = $refreshTokenGenerator;
    }

    //<editor-fold desc="Register">
    #[Route(path: '/register', name: 'auth_register', methods: ['POST'])]
    public function register(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
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
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString, Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $em->persist($user);
        $em->flush();

        $JWTtoken = $this->jwtManager->create($user);
        //$refreshtoken = $this->refreshTokenGenerator->createForUserWithTtl($user, $this->getParameter('token.ttl'));

        return new JsonResponse([
            'token' => $JWTtoken,
            //'refresh_token' => $refreshtoken->getRefreshToken(),
            //'refresh_token_expiration' => $refreshtoken->getValid()->getTimestamp()
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