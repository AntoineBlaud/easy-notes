<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\DocumentRepository;

class uploadaudioController extends AbstractController{

    private $documentRepository;
    private $userRepository;
    private $em;


    public function __construct(DocumentRepository $documentRepository,
     UserRepository $userRepository,  ObjectManager $em){

        $this->documentRepository = $documentRepository;
        $this->UserRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Route("/uploadaudio", name="uploadaudio")
     */
    public function uploadAudio():Response
    {
    $uniqid = $this->getUser()->getOpenedEdit();
    $doc = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
    $path = "users/".$this->getUser()->getUsername()."/projects".$doc->getPath()."/audio_in_progress/";

    $file = $_FILES["file"];
    $target = $path.$file["name"].".webm";
    move_uploaded_file( $file["tmp_name"], $target);
    $input = $target;
    $output = $path.$file["name"].".flac";
    $out = exec("ffmpeg  -y -i ".$input." -acodec flac -ac 1 ".$output);
    unlink($input);


    return $this->forward('App\Controller\GoogleRequestController::sendGoogleAudioRequest',[
        "audio" => $output
    ]);

    }
    /**
     * @Route("/getlongaudio", name="getLongAudio")
     */
    public function getLongAudio():Response
    {

        $uniqid = $this->getUser()->getOpenedEdit();
        $doc = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
        $path = "users/".$this->getUser()->getUsername()."/projects".$doc->getPath();

        
        // list files
        $files = scandir($path."/audio_in_progress/");
        $infos = "";
        // delete previous big file if exist
        foreach($files as $file){
            // If the file is a flac
            if(preg_match('/[a-zA-Z0-9_]*.flac/',$file))
                $infos.= "file ".$file.PHP_EOL;
        } 

        // delete previous long audio
        $files = scandir($path."/audio_saved/");
        // delete previous big file if exist
        foreach($files as $file){
            // If the file is a flac
            if(preg_match('/long.*/', $file))
                //  Move file in order to be read by ffmpeg
                rename($path."/audio_saved/".$file, $path."/audio_in_progress/".$file);
        } 

        // add  listed files to list.txt
        file_put_contents($path."/audio_in_progress/"."list.txt",$infos);

        // concat all flac file by orders
        sleep(1);
        $newFile = "long".rand(0,500).".flac";
        $filename = $path."/audio_saved/".$newFile;
        exec("ffmpeg -f concat -i ".$path."/audio_in_progress/"."list.txt ".$filename);
        sleep(1);

        // delete concatened files
        $files = scandir($path."/audio_in_progress/");
        foreach($files as $file){
            if(preg_match('/[a-zA-Z0-9_]+./',$file))
                unlink($path."/audio_in_progress/".$file);
        } 

        //update list.txt
        file_put_contents($path."/audio_in_progress/"."list.txt",$filename);
        

        // This should return the file to the browser as response
        return new Response($filename);


    }

    
}



?>