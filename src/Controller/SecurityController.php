<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends AbstractController{

    private $userRepository;
    private $em;

     public function __construct(UserRepository $userRepository, ObjectManager $em)
     {
         $this->userRepository = $userRepository;
         $this->em = $em;
         
     }
     /**
     * @Route("/login", name="login")
     * 
     */
    public function login(AuthenticationUtils $authentificationUtils):Response
    {
        $error = $authentificationUtils->getLastAuthenticationError();
        $lastUsername = $authentificationUtils->getLastUsername();
        if($this->getUser())
            return $this->redirectToRoute('project');
        else{
            return $this->render('security/login.html.twig',
        [
            'last_username' => $lastUsername,
            'error' => $error,
            'message' =>null
        ]);
        }

    }
    /**
     * @Route("/logout", name="logout")
     * 
     */
    public function logout()
    {
    }
     /**
     * @Route("/signup", name="signup")
     */
    public function signup(Request $request)
    {
        // Si utilisateur connecté, on le redirige vers project
        if($this->getUser())
        return $this->redirectToRoute('project');

        $user = new User();
        $form = $this->createForm(SignType::class ,$user);
        $form->handleRequest($request);
        $error = null;
        if($form->isSubmitted() && $form->isValid())
        {

            //Now verify data
             $data = $form->getData();
             $username = $data->getUsername();
             $password = $data->getPassword();
             $confirmedPassword = $data->getConfirmedPassword();
             $error = array();
            
            $found = $this->userRepository->findOneByUsername($username);
            // check if username not exist
            if(empty($found))
            {
                // check is password correspond
                if($password == $confirmedPassword)
                    {
                        $this->em->persist($user);
                        $this->em->flush();

                        // création du répertoire personnel
                        chdir('users');
                        mkdir($username);
                        chdir($username);
                        mkdir('audio');
                        return $this->render("security/login.html.twig",[
                        'error' => null,
                        'message' =>"Account created !"
                        ]);
                    }
                else
                    array_push($error, "Passwords doesn't matches");
                
            }else
                array_push($error, "Username already used");
        }
        return $this->render("security/signup.html.twig",[
        'property' =>$user,
        'form' => $form->createView(),
        'error'=>$error
        ]);
    }
   /**
     * @Route("/download", name="download")
     */
    public function accessDownloadableFile(Request $request) :Response
    {
        // verifier authentification, fichier existe/ retourner fichier ou retourner page fichier existe pas
        $current_user = $this->getUser();
        $path = $request->query->get('file');
        return new Response("voila");

    }




}



?>