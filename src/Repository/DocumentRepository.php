<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findDocument($user, $parentFolder, $name)
    {
        if($parentFolder == NULL)
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p
                FROM App\Entity\Document p
                WHERE p.owner = :user AND p.parentFolder is NULL AND p.name =:name'
            )->setParameters(array('user'=>$user, 'name'=>$name));
    
            return $query->execute();
        }
        else
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p
                FROM App\Entity\Document p
                WHERE p.owner = :user AND p.parentFolder =:parentFolder AND p.name =:name'
            )->setParameters(array('user'=>$user,'parentFolder'=>$parentFolder,
        'name'=>$name));
    
            return $query->execute();

        }
    }

    public function findDocumentsInDir($user, $parentFolder)
    {
        if($parentFolder == NULL)
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p 
                FROM App\Entity\Document AS p
                WHERE p.owner = :user AND p.parentFolder is NULL'
            )->setParameters(array('user'=>$user));
    
            return $query->execute();
        }
        else
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p 
                FROM App\Entity\Document AS p
                WHERE p.owner = :user AND p.parentFolder = :parentFolder'
            )->setParameters(array('user'=>$user,'parentFolder'=>$parentFolder));
    
            return $query->execute();

        }
    }

    public function findDocumentWithUniqId($uniqid)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
        'SELECT p 
            FROM App\Entity\Document AS p
            WHERE p.uniqid = :uniqid '
        )->setParameters(array('uniqid'=>$uniqid));

        return $query->execute();

    }

}