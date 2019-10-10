<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class EditController extends AbstractController{

    /**
     * @Route("/edit", name="edit")
     */
    public function index():Response
    {
        return $this->render('edit.html.twig');

    }
}



?>