<?php

namespace AppBundle\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class mp3DataExtract
{

    private $parsedFile;
    private $mp3MetaData = [];

    public function __construct(UploadedFile $uploadedFile)
    {

        $getId3 = new \getID3();

        $this->parsedFile = $getId3->analyze($uploadedFile->getFileInfo()->getPathname());

    }


    public function extractMp3Data()
    {

        //merge tag version entries into one
        \getid3_lib::CopyTagsToComments($this->parsedFile);

        //var_dump($this->parsedFile['tags']);
        //die();

        $this->mp3MetaData = $this->parsedFile['comments'];

    }

    /**
     * @return array
     */
    public function getMp3Data()
    {

        return $this->mp3MetaData;

    }



}