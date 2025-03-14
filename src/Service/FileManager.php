<?php
// src/AppBundle/Utility/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{

    protected $targetDirectory;
    private $validExtensions = array("json");

    public function __construct($targetDirectory = "/tmp/")
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $allowedExtensions = array())
    {
        $returnData = array();
        if(isset($allowedExtensions[0])) {
            $this->validExtensions = $allowedExtensions;
        }
        try {
            $mimeType = $file->getMimeType();
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $originalExtension = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
            if($this->validExtensions[0] == "all" || in_array($originalExtension,$this->validExtensions)) {                
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
                $file->move($this->getTargetDirectory(), $fileName);
                $contents = file_get_contents($this->getTargetDirectory() . $fileName);
            } else {
                $failMessage = "You have attempted to upload a file type that is not supported by this system.<br />" .
                "You uploaded type: <strong>" . strtoupper($originalExtension) . "</strong><br />" .
                "Valid file types are <strong>" . implode(", ", $this->validExtensions) . "</strong>";
                return array();
            }
            

            $returnData = array(
                "originalFileName" => $file->getClientOriginalName(),
                "newFileName" => $file->getClientOriginalName(),
                "mimeType" => $mimeType,
                "targetPath" => $this->getTargetDirectory(),
                "fileUploadPath" => $this->getTargetDirectory() . $fileName,
                "fileContents" => $contents,
            );

        } catch (FileException $e) {

        }

        unlink($this->getTargetDirectory() . $fileName);
        return $returnData;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }


    public static function codeToMessage($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the 10MB.  You will need to make the file smaller.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

        }
        return $message;
    }

    public static function getImgTag($imageContent, $mimeType, $width=200, $height=200) {
        $content = stream_get_contents($imageContent);
        return '<img src="data:' . $mimeType . ';base64,' . base64_encode($content) . '" width="' . $width . '" height="' . $height . '"/>';
    }

    public static function getDataTag($imageContent, $mimeType) {
        $content = stream_get_contents($imageContent);
        return 'data:' . $mimeType . ';base64,' . base64_encode($content) . '"';
    }

    public static function getFileContents($fileContent) {
        $content = stream_get_contents($fileContent);
        return $content;
    }


}
