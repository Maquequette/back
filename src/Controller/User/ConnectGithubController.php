<?php

namespace App\Controller\User;

use ApiPlatform\Exception\ItemNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ConnectGithubController extends AbstractController
{

    public function __construct(private readonly HttpClientInterface $client) { }

    public function __invoke( Request $request): JsonResponse
    {
        $code = $request->request->get('code');
        $redirectUri = $request->request->get('redirectUri');

        if(!$code || !$redirectUri){
            throw new ItemNotFoundException('miss mandatory field');
        }

        $response = $this->client->request(
            'POST',
            'https://github.com/login/oauth/access_token',
            [
                'body' => [
                    'client_id' => $this->getParameter('GITHUB_CLIENT_ID'),
                    'client_secret' => $this->getParameter('GITHUB_CLIENT_SECRET'),
                    'code' => $code,
                    'redirection_uri' => $redirectUri
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
    }
}