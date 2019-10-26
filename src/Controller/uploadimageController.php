<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\DocumentRepository;

class uploadimageController extends AbstractController{

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

        $uniqid = $this->getUser()->getOpenedEdit();
        $doc = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
        $path = "users/".$this->getUser()->getUsername()."/projects".$doc->getPath()."/image_in_progress/";


        for($i=0; $i < $n; $i++){
            move_uploaded_file($files["tmp_name"][$i],$path.$files["name"][$i]);
            $received = true;
        }
        return $this->redirectToRoute('capture',[
            'received'=>$received
        ]);

    }

     /**
     * @Route("/saveimage", name="saveimage")
     */
    public function saveImage(Request $request)
    {


    }
     /**
     * @Route("/imageToText", name="imageToText")
     */
    public function imageToText(): Response
    {
        $files = $_POST["file"];
        return $this->forward('App\Controller\GoogleRequestController::sendGoogleImageToTextRequest',[
            "image" => $files
        ]);

    }

    
}



?>