<?php

namespace App\Entity;

//pour la création de l'entité en BDD

//vérifie les saisies et leurs validations


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 * 
 */
class Product {

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
     * @ORM\Column(type="text", )
     * @Assert\Length (min=15)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @var User owner
     */
    private $owner;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="products", cascade="persist")
     * @var Collection
     */
    private $tags;
    
    /**
     * @ORM\OneToMany(targetEntity="Loan", mappedBy="product")
     * @var Loan
     */
    private $loans;
    
    public function __construct() {
        $this->tags = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"insertion"})
     * @var object
     * @Assert\Image(maxSize = "2M",minWidth="200", minHeight="200")
     */
    private $image;

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(string $description): self {
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

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }

    public function getTags(): Collection {
        return $this->tags;
    }
    public function setTags(Collection $tags) {
        $this->tags = $tags;
        return $this;
    }
        
    //Add a tag with a verification
    //si le tags contient déjà le tag concerné stop it
    public function addTag($tag){
        if($this->tags->contains($tag)){
            return;
        }
        $this->tags->add($tag);
        //on ajoute le produit courant au tag
        $tag->getProducts()->add($this);
    }
    
    public function getLoans(): Collection{
        return $this->loans;
    }

    public function setLoans(Collection $loans) {
        $this->loan = $loans;
        return $this;
    }



}
