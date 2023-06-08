<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Service\AwsS3Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AwsController extends AbstractController
{
    private $awsS3Service;

    public function __construct(AwsS3Service $awsS3Service)
    {
        $this->awsS3Service = $awsS3Service;
    }
     /**
     * @Route("/upload", name="upload_file", methods={"POST"})
     */
    public function uploadFile(Request $request)
    {
        $file = $request->files->get('file');
        // Vérifiez si un fichier a été envoyé
        if (!$file) {
            return new Response('Aucun fichier n\'a été envoyé.', Response::HTTP_BAD_REQUEST);
        }
        
        //$key = './'.$file->getClientOriginalName();
        
        $sourceFilePath = $file->getPath();

        $uploadSuccessful =  $this->awsS3Service->uploadFile('/fichierpostman',$sourceFilePath);
       
        if ($uploadSuccessful) {
            return new Response('Le fichier à bien été uploader.', Response::HTTP_CREATED);
        }else{
            return new Response("Le fichier n'a pas été uploader.", Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

}
