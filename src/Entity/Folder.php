<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FolderRepository")
 */
class Folder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="parentFolder")
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Folder", inversedBy="Folders")
     * @ORM\JoinColumn(nullable=true)
     */
    private $parentFolder;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Folder", mappedBy="parentFolder")
     */
    private $folders;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="folders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->Folders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setParentFolder($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getParentFolder() === $this) {
                $document->setParentFolder(null);
            }
        }

        return $this;
    }

    public function getParentFolder(): ?self
    {
        return $this->parentFolder;
    }

    public function setParentFolder(?self $parentFolder): self
    {
        $this->parentFolder = $parentFolder;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFolders(): Collection
    {
        return $this->Folders;
    }

    public function addFolder(self $folder): self
    {
        if (!$this->Folders->contains($folder)) {
            $this->Folders[] = $folder;
            $folder->setParentFolder($this);
        }

        return $this;
    }

    public function removeFolder(self $folder): self
    {
        if ($this->Folders->contains($folder)) {
            $this->Folders->removeElement($folder);
            // set the owning side to null (unless already changed)
            if ($folder->getParentFolder() === $this) {
                $folder->setParentFolder(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
