<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ExchangeDataController extends AbstractController{

    /**
     * @Route("/getAudioTranscript", name="getAudioTranscript.index")
     */

    private $transcript = array();

    public function getAudioTranscript():Response
    {
        return $this->render('home.html.twig');

    }
}



?>