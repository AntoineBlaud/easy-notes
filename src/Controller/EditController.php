<?php

namespace App\Controller;

use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{

    private $__documentRepository;
    private $__userRepository;
    private $__em;

    public function __construct(DocumentRepository $documentRepository,
        UserRepository $userRepository, ObjectManager $em) {

        $this->documentRepository = $documentRepository;
        $this->UserRepository     = $userRepository;
        $this->em                 = $em;
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function index(Request $request): Response
    {
        $uniqid = $request->query->get("doc");
        $this->getUser()->setOpenedEdit($uniqid);
        $this->em->persist($this->getUser());
        $this->em->flush();

        $doc   = $this->documentRepository->findDocumentWithUniqId($uniqid)[0];
        $audio = $path = "users/" . $this->getUser()->getUsername() . "/projects" . $doc->getPath() . "/audio_saved/";
        $files = scandir($path);
        foreach ($files as $file) {
            // If the file is a flac
            if (preg_match('/[a-zA-Z0-9_]*.flac/', $file)) {
                $audio .= $file;
            }

        }

        return $this->render('edit.html.twig', [
            "audio" => $audio,
        ]);

    }
}
