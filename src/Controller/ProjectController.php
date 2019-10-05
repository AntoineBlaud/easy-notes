<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Folder;
use App\Form\DocumentType;
use App\Form\FolderType;
use App\Repository\DocumentRepository;
use App\Repository\FolderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController{

    private $userRepository;
    private $documentRepository;
    private $folderRepository;
    private $em;
    private $user;

    public function __construct(DocumentRepository $documentRepository, UserRepository $userRepository,
    FolderRepository $folderRepository, ObjectManager $em)
    {
        $this->userRepository = $userRepository;
        $this->documentRepository = $documentRepository;
        $this->folderRepository = $folderRepository;
        $this->em = $em;
        
    }

    /**
     * @Route("/project/", name="project")
     */
    public function rootindex(Request $request):Response
    {
        $create_folder = new Folder();
        $this->user = $this->getUser();
        $create_document = new Document();
        $form_folder = $this->createForm(FolderType::class, $create_folder);
        $form_document = $this->createForm(DocumentType::class, $create_document);
        $form_document->handleRequest($request);
        $form_folder->handleRequest($request);

        // Verifier si nom n'existe pas
        if($form_folder->isSubmitted() && $form_folder->isValid())
        {
            $name = $form_folder->getData()->getName();
            $found = $this->folderRepository->findFolder($this->user,$name);
            if(empty($found))
            {
                $create_folder->setOwner($this->user);
                $this->em->persist($create_folder);
                $this->em->flush();
            }

        }
        if($form_document->isSubmitted() && $form_document->isValid())
        {
            $name = $form_document->getData()->getName();
            $found = $this->documentRepository->findDocument($this->user,null,$name);
            if(empty($found))
            {
                $create_document->setOwner($this->user);
                $this->em->persist($create_document);
                $this->em->flush();
            }
        }

        
        $documents = $this->documentRepository->findDocumentsInDir($this->user,null);
        $folders = $this->folderRepository->findFoldersInDir($this->user, null);


        return $this->render('project.html.twig',[
            'form_folder'=>$form_folder->createView(),
            'form_document'=>$form_document->createView(),
            'documents'=>$documents,
            'folders'=>$folders,
            'create_folder'=>true,
            'create_document'=>true,
            'return'=>false
    ]);

    }
     /**
     * @Route("/project/{folder}", name="project.folder")
     */
    public function index(Request $request, String $folder):Response
    {
        // Verifier si le folder existe sinon rediriger
        $create_folder = new Folder();
        $form_folder = $this->createForm(FolderType::class, $create_folder);
        $this->user = $this->getUser();
        $create_document = new Document();
        $form_document = $this->createForm(DocumentType::class, $create_document);
        $form_document->handleRequest($request);

        if($form_document->isSubmitted() && $form_document->isValid())
        {
            $name = $form_document->getData()->getName();
            $found = $this->documentRepository->findDocument($this->user,null,$name);
            if(empty($found))
            {
                $f = $this->folderRepository->findFolder($this->user,$folder);
                $create_document->setOwner($this->user);
                $create_document->setParentFolder(array_pop($f));
                $this->em->persist($create_document);
                $this->em->flush();
            }
        }

        $dir = $this->folderRepository->findFolder($this->user,$folder);
        $documents = $this->documentRepository->findDocumentsInDir($this->user,$dir);
        $folders = $this->folderRepository->findFoldersInDir($this->user, $dir);


        return $this->render('project.html.twig',[
            'form_folder'=>$form_folder->createView(),
            'form_document'=>$form_document->createView(),
            'documents'=>$documents,
            'folders'=>$folders,
            'create_folder'=>false,
            'create_document'=>true,
            'return'=>true
    ]);

    }
}



?>