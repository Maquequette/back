<?php

namespace App\Controller\Resource;

use ApiPlatform\Exception\ItemNotFoundException;
use App\Entity\PolymorphicEntity;
use App\Entity\Resource;
use App\Service\AwsS3Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ResourceController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AwsS3Service $awsS3Service
    ){ }

    public function validateResources(Request $request, PolymorphicEntity $target): array
    {
        $user = $this->getUser();
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

                // create a Resource
                $item = new Resource();
                $item->setLabel($resource['label']);
                $item->setType($resource['type']);
                $item->setValue($filepath);
                $item->setTarget($target);
                $items[] = $item;

                $this->em->persist($item);
            }
        }

        return $items;
    }
}