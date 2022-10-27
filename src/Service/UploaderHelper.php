<?php
namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploaderHelper {

    private $uploadsPath;
    public function __construct(string $uploadsPath)
    {
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadImage(UploadedFile $uploadedFile){

        $destination = $this->uploadsPath."/uploads";
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = "uploads/".uniqid().'.'.$uploadedFile->guessExtension();
        
        
        try {
            $uploadedFile->move($destination, $newFilename);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            $e->getMessage();
        }

        return $newFilename;
    }


}