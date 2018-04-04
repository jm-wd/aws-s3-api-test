<?php

namespace AppBundle\Service;

use AppBundle\File\StorageInterface;
use Aws\Exception\MultipartUploadException;
use Aws\S3\S3Client;
use Aws\S3\MultipartUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AwsS3Service implements StorageInterface
{

    private $bucket;
    private $s3Client;

    /**
     * @param string $bucket
     * @param array $client
     */
    public function __construct($bucket, array $client)
    {

        $this->bucket = $bucket;
        $this->s3Client = new S3Client($client);

    }

    public function uploadFile(UploadedFile $uploadedFile, $directory, $newFileName = null, $acl = 'public-read')
    {

        //file path and name
        $file = $uploadedFile->getFileInfo()->getPathname();

        if(!isset($newFileName)) {
            $newFileName = time() . '-' . $uploadedFile->getClientOriginalName();
        }

        //Use multi part uploader as it allow for large file transfers if needed
        //Content type detected via aws
        $uploader = new MultipartUploader($this->s3Client, $file, [
            'bucket' => $this->bucket,
            'key' => $directory . '/' . $newFileName,
            'ContentType' => $uploadedFile->getMimeType(),
            'ACL' => $acl
        ]);

        //Allow upload to resume if error occurs
        do {

            try {

                $result = $uploader->upload();

            } catch (MultipartUploadException $e) {

                $uploader = new MultipartUploader($this->s3Client, $file, [
                    'state' => $e->getState(),
                ]);

            }

        } while (!isset($result));

        return urldecode($result['ObjectURL']);

    }

}