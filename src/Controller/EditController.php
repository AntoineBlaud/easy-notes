<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class EditController extends AbstractController{

    private $documentRepository;
    private $em;

    public function __construct(DocumentRepository $documentRepository, ObjectManager $em){

        $this->documentRepository = $documentRepository;
        $this->em = $em;
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function index(Request $request):Response
    {
        $uniqid = $request->query->get("doc");
        $doc = $this->documentRepository->findDocumentWithUniqId($uniqid);
        return $this->render('edit.html.twig');

    }
}



?>