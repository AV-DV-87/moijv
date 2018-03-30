<?php

namespace App\Entity;

//pour la création de l'entité en BDD
use Doctrine\ORM\Mapping as ORM;

//vérifie les saisies et leurs validations
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 * 
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Length (min=2, max=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length (min=15)
     */
    private $description;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="Product")
     * @var User Owner
     */
    private $owner;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getOwner(): User {
        return $this->owner;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
        return $this;
    }


}
