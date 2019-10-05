<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class DocumentType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options){

        $builder->add('name',null,array('label' => 'Document name:'));
        $builder->add('description',null,array('label' => 'Description:'));

    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' =>Document::class]);
    }

}

