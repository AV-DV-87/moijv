<?php

namespace App\Entity;
//on utilise tout le namespace constraints avec l'alias Assert
//il permet d'implémenter la validation des formulaires


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User implements UserInterface, \Serializable
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length (min=5, max=50)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registerDate;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $roles;
        
    /**
     * @ORM\OneToMany(targetEntity="Product", mappedBy="Owner")
     * @var Collection products
     */
    //one to many créer une relation 1-n entre table product et user
    private $products;
    
    public function __construct() {
        $this->products = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }
    
    //INTERFACE USER ET SERIALIZABLE
    //DOC : https://symfony.com/doc/current/security/entity_provider.html
    //vide sert à supprimé des données utilisateurs à certains point de son
    //parcours
    public function eraseCredentials() {
        
    }
    
    //différents roles des utilisateurs admin/user...
      
    public function getRoles() {
        //la fonction set role attend un tableau
        //explode crééra un tableau à partir de notre liste de roles
        // qui sera sous la forme user|admin|...
        return explode('|', $this->roles);
    }
    
    public function setRoles($roles) {
        $this->roles = $roles;
    }
    //config du grain de sel pas utile dans notre cas
    public function getSalt() {
        return NULL;
    }
    //utile à la mise des données de l'utilisateur en SESSION
    //transformation en tableau
    public function serialize(): string {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    public function getProducts(): Collection
    {
        return $this->products;
    }

    

}
