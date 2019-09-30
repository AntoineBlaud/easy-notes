<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class uploadaudioController extends AbstractController{


    /**
     * @Route("/uploadaudio", name="uploadaudio.getAudio")
     */
    public function getAudio():Response
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
}



?>