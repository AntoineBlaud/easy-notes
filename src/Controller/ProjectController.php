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
     * @Route("/projects/", name="projects")
     */
    public function rootindex(Request $request):Response
    {
        // Create Form and entity
        $create_folder = new Folder();
        $this->user = $this->getUser();
        $create_document = new Document();
        $form_folder = $this->createForm(FolderType::class, $create_folder);
        $form_document = $this->createForm(DocumentType::class, $create_document);
        $form_document->handleRequest($request);
        $form_folder->handleRequest($request);
        $username = $this->user->getUsername();

        if($form_folder->isSubmitted() && $form_folder->isValid())
        {
            $name = $form_folder->getData()->getName();
            $found = $this->folderRepository->findFolder($this->user,$name);
            if(empty($found))
            {
                $create_folder->setOwner($this->user);

                // persist
                $this->em->persist($create_folder);
                $this->em->flush();

                // create folder
                chdir("users/".$username."/projects/");
                mkdir($name);

            }

        }
        if($form_document->isSubmitted() && $form_document->isValid())
        {
            $name = $form_document->getData()->getName();
            $found = $this->documentRepository->findDocument($this->user,null,$name);
            if(empty($found))
            {
                // Set document infos
                $create_document->setOwner($this->user);
                $create_document->setUniqid(uniqid('',true));

                // persist
                $this->em->persist($create_document);
                $this->em->flush();
                 
                // create folder
                $path = "users/".$username."/projects/";
                $this->createDocumentFolders($path,$name);

                
            }
        }


           // Print pages
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
     * @Route("/projects/{folder}", name="project.folder")
     */
    public function index(Request $request, String $folder):Response
    {
        // Create form and entity
        $create_folder = new Folder();
        $form_folder = $this->createForm(FolderType::class, $create_folder);
        $this->user = $this->getUser();
        $create_document = new Document();
        $form_document = $this->createForm(DocumentType::class, $create_document);
        $form_document->handleRequest($request);
        $username = $this->user->getUsername();

        if($form_document->isSubmitted() && $form_document->isValid())
        {
            $name = $form_document->getData()->getName();
            $found = $this->documentRepository->findDocument($this->user,null,$name);
            if(empty($found))
            {
                // Set document infos
                $f = $this->folderRepository->findFolder($this->user,$folder);
                $parent_folder = array_pop($f);
                $create_document->setOwner($this->user);
                $create_document->setUniqid(uniqid('',true));
                $create_document->setParentFolder($parent_folder);

                // persist
                $this->em->persist($create_document);
                $this->em->flush();

                // Create folder
                $path = "users/".$username."/projects/".$parent_folder->getName()."/";
                $this->createDocumentFolders($path,$name);
            }
        }

        // Print pages

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

    public function createDocumentFolders($path,$name)
    {
        chdir($path);
        mkdir($name);
        chdir($name);
        mkdir("audio_in_progress");
        mkdir("audio_saved");
        mkdir("image_in_progress");
        mkdir("image_saved");

    }
}



?>