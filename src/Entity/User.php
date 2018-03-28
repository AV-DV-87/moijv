<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
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
    
    

        public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegisterDate(): \DateTimeInterface
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

}
