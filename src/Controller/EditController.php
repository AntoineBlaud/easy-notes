<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class EditController extends AbstractController{

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
     * @Route("/edit", name="edit")
     */
    public function index(Request $request):Response
    {
        $uniqid = $request->query->get("doc");
        $this->getUser()->setOpenedEdit($uniqid);
        $doc = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
        $this->em->persist($this->getUser());
        $this->em->flush();

        return $this->render('edit.html.twig');

    }
}



?>