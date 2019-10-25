<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController{

    private $connected;

    /**
     * @Route("/", name="home")
     */
    public function index():Response
    {
        if($this->getUser())
            $this->connected = true;
        else
            $this->connected = false;
        return $this->render('home.html.twig',
    ["connected" =>$this->connected]);

    }
}



?>