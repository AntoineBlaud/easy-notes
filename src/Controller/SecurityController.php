<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('security/login.html.twig',
    [
        'last_username' => $lastUsername,
        'error' => $error
    ]);

    }
     /**
     * @Route("/signup", name="signup")
     */
    public function signup(Request $request)
    {
        $user = new User();
        $form = $this->createForm(SignType::class ,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
             $this->em->persist($user);
             $this->em->flush();
             return $this->render("security/login.html.twig");

        }
        return $this->render("security/signup.html.twig",[
        'property' =>$user,
        'form' => $form->createView()
        ]);
    }





}



?>