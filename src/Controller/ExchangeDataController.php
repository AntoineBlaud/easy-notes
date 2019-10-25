<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Document;

class ExchangeDataController extends AbstractController{


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
     * @Route("/getcapturedimages", name="getcapturedimages")
     */

    public function getCapturedImages():Response
    {
        $uniqid = $this->getUser()->getOpenedEdit();
        $doc = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
        $tmp = $doc->getPath();
        $tmp2 = $this->getUser()->getUsername();
        $path = "users/".$this->getUser()->getUsername()."/projects".$doc->getPath()."/image_in_progress/";
        $scannedDir = scandir($path);
        unset($scannedDir[0]);
        unset($scannedDir[1]);
        return new Response(implode("::",$scannedDir));        

    }
}



?>