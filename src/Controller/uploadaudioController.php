<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;

class uploadaudioController extends AbstractController{


    /**
     * @Route("/uploadaudio", name="uploadaudio")
     */
    public function uploadAudio():Response
    {
    $file = $_FILES["file"];
    $target = "audio/".$file["name"].".webm";
    move_uploaded_file( $file["tmp_name"], $target);
    $input = $target;
    $output = "audio/".$file["name"].".flac";
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
        // list files
        $files = scandir("audio/");
        $infos = "";
        // delete previous big file if exist
        foreach($files as $file){
            // If the file is a flac
            if(preg_match('/[a-zA-Z0-9_]*.flac/',$file))
                $infos.= "file ".$file.PHP_EOL;
            if(preg_match('/long.*/', $file))
                unlink("audio/".$file);
        } 
        // add the file to the list
        file_put_contents("audio/list.txt",$infos);
        // concat all flac file by orders
        sleep(1);
        $filename = "audio/long".rand(0,500).".flac";
        exec("ffmpeg -f concat -i audio/list.txt ".$filename);

        // This should return the file to the browser as response
        return new Response($filename);


    }

    
}



?>