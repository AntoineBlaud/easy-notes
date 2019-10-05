<?php

namespace App\Repository;

use App\Entity\Folder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Folder|null find($id, $lockMode = null, $lockVersion = null)
 * @method Folder|null findOneBy(array $criteria, array $orderBy = null)
 * @method Folder[]    findAll()
 * @method Folder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Folder::class);
    }

    public function findFolder($user, $name)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
           'SELECT p
            FROM App\Entity\Folder p
            WHERE p.owner = :user  AND p.name =:name'
        )->setParameters(array('user'=>$user,'name'=>$name));
 
        return $query->execute();
    }
    public function findFoldersInDir($user, $parentFolder)
    {
    
        if($parentFolder == NULL)
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p 
                FROM App\Entity\Folder AS p
                WHERE p.owner = :user AND p.parentFolder is NULL'
            )->setParameters(array('user'=>$user));
    
            return $query->execute();
        }
        else
        {
            $entityManager = $this->getEntityManager();
            $query = $entityManager->createQuery(
            'SELECT p 
                FROM App\Entity\Folder AS p
                WHERE p.owner = :user AND p.parentFolder = :parentFolder'
            )->setParameters(array('user'=>$user,'parentFolder'=>$parentFolder));
    
            return $query->execute();

        }
    }
}
