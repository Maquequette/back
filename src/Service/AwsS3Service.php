<?php

namespace App\Service;

use ApiPlatform\Exception\ItemNotFoundException;
use Aws\Exception\AwsException;
use Aws\Exception\UnresolvedApiException;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AwsS3Service
{

    private S3Client $s3Client;
    private string $AWS_BUCKET;

    public function __construct(
        string $AWS_BUCKET,
        string $AWS_DEFAULT_REGION,
        string $AWS_ACCESS_KEY_ID,
        string $AWS_SECRET_ACCESS_KEY
    ){
        $this->AWS_BUCKET = $AWS_BUCKET;
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $AWS_DEFAULT_REGION,
            'credentials' => [
                'key' => $AWS_ACCESS_KEY_ID,
                'secret' => $AWS_SECRET_ACCESS_KEY,
            ],
        ]);
    }

    public function uploadFile(string $path, $file): string {

        // Check file existence
        if (!$file) {
            throw new ItemNotFoundException('file not found');
        }

        $key = $path.'/'.$file->getClientOriginalName();
        return $this->uploadS3($key, $file);
    }

    protected function uploadS3(string $key, $sourceFile): string {

        $result = $this->s3Client->putObject([
            'Bucket'=>$this->AWS_BUCKET,
            'Key' => $key,
            'SourceFile' => $sourceFile,
        ]);
        //dd($result);
        if($result['@metadata']['statusCode'] !== 200) {
            throw new UnresolvedApiException();
        }

        return $result['@metadata']['effectiveUri'];
    }
}