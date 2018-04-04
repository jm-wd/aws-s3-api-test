<?php

namespace AppBundle\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface StorageInterface
{

    /**
     * @param UploadedFile $uploadedFile
     * @param string $directory
     * @param string $newFileName
     * @param string $acl
     *
     * @return mixed
     */
    public function uploadFile(UploadedFile $uploadedFile, $directory, $newFileName = null, $acl = null);

    //delete file etc.

}