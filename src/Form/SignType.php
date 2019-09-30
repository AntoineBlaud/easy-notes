<?php

namespace App\Form;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;


class SignType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add('username');
        $builder->add('password');
        $builder->add("confirmpassword");

    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' =>User::class]);
    }

}






?>