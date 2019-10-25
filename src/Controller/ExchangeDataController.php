<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ExchangeDataController extends AbstractController{

    /**
     * @Route("/getcapturedimages", name="getcapturedimages")
     */

    public function getCapturedImages():Response
    {
        $this->getUser();
        $dir = "uploaded_images/";
        $scannedDir = scandir($dir);
        unset($scannedDir[0]);
        unset($scannedDir[1]);
        return new Response(implode("::",$scannedDir));        

    }
}



?>