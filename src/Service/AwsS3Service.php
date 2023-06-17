<?php

namespace App\Service;

use Aws\S3\S3Client;



class AwsS3Service
{
    private $s3Client;
    private $AWS_BUCKET;
    private $AWS_ACCESS_KEY_ID;
    private $AWS_SECRET_ACCESS_KEY;
        public function __construct( string $AWS_BUCKET , string $AWS_DEFAULT_REGION ,string $AWS_ACCESS_KEY_ID,string $AWS_SECRET_ACCESS_KEY)
        {   
            $this->AWS_BUCKET=$AWS_BUCKET;
            $this->AWS_ACCESS_KEY_ID=$AWS_ACCESS_KEY_ID;
            $this->AWS_SECRET_ACCESS_KEY=$AWS_SECRET_ACCESS_KEY;

            $this->s3Client = new S3Client([

                'version' => 'latest',
                'region' => $AWS_DEFAULT_REGION,
                'credentials' => [
                    'key' => $AWS_ACCESS_KEY_ID,
                    'secret' => $AWS_SECRET_ACCESS_KEY,
                ],
            ]);
        }


    public function uploadFile(string $key,  $sourceFilePath): bool
    {
        //dd([$this->AWS_ACCESS_KEY_ID, $this->AWS_SECRET_ACCESS_KEY]);
        $result = $this->s3Client->putObject([
            'Bucket'=>$this->AWS_BUCKET,
            'Key' => $key,
            'SourceFile' => $sourceFilePath,
            
        ]);
        return $result['@metadata']['statusCode'] === 200;
    }

    // Autres méthodes pour interagir avec AWS S3*/
}