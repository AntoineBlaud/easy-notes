<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class uploadimageController extends AbstractController{

    /**
     * @Route("/capture", name="capture")
     */
    public function index(Request $request):Response

    {
        $received = $request->query->get('received');
        return $this->render('phone.html.twig',
        [
            'received'=>$received
        ]);

    }
     /**
     * @Route("/uploadimage", name="uploadimage")
     */
    public function uploadimage():Response
    {
        $received = false;
        $files = $_FILES["images"];
        $n = count($files["name"]);
        for($i=0;$i<$n;$i++){
            move_uploaded_file($files["tmp_name"][$i],"uploaded_images/".$files["name"][$i]);
            $received = true;
        }
        return $this->redirectToRoute('capture',[
            'received'=>$received
        ]);

    }
}



?>