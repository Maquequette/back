<?php

namespace App\Controller\Ressource;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\AwsS3Service;
use Symfony\Component\Validator\ConstraintViolationList;

class CreateRessourceController extends AbstractController
{
    private $awsS3Service;

    private Security $security;

    public function __construct(Security $security,AwsS3Service $AwsS3Service){
        $this->security = $security;
        $this->awsS3Service = $AwsS3Service;

    }

    public function __invoke(SerializerInterface $serializer,Request $request): Response
    {   
        try {
            $user = $this->security->getUser();
            $file = $request->files->get('Ressource');
    
            $context = (new ObjectNormalizerContextBuilder())
                ->withGroups('User');
            
            /*$data = $serializer->serialize(
                data: $user,
                format: 'json',
                context: $context->toArray()
    
            );*/
            // Vérifiez si un fichier a été envoyé
            
            if (!$file) {
                
                throw new ValidationException(new ConstraintViolationList(), message: 'Le fichier est vide', code: 400);
            }
            
            $key = './'.$file->getClientOriginalName();
            $uploadSuccessful =  $this->awsS3Service->uploadFile($key,$file);
           
            if ($uploadSuccessful) {
                return new Response('Le fichier à bien été uploader.', Response::HTTP_CREATED);
            }else{
                return new Response("Le fichier n'a pas été uploader.", Response::HTTP_INTERNAL_SERVER_ERROR);
    
            }        
        } 
            catch (ValidationException $ve) {
            //throw $th;
            new Response('Aucun fichier n\'a été envoyé.', Response::HTTP_BAD_REQUEST);
            }
        }
        
    }
