<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $role = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="num_tel", type="string", length=50, nullable=false)
     */
    private $numTel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $image = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="bio", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $bio = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_formateur", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $isFormateur = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_livreur", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $isLivreur = 'NULL';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_vendeur", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $isVendeur = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="cv", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $cv = NULL;

    /**
     * @var bool
     *
     * @ORM\Column(name="validite", type="boolean", nullable=false)
     */
    private $validite;

    /**
     * @var bool
     *
     * @ORM\Column(name="mailconfirme", type="boolean", nullable=false)
     */
    private $mailconfirme;

    /**
     * @var bool
     *
     * @ORM\Column(name="numconfirme", type="boolean", nullable=false)
     */
    private $numconfirme;

    /**
     * @var int
     *
     * @ORM\Column(name="avertissement", type="integer", nullable=false)
     */
    private $avertissement;

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }

    public function setNumTel(string $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getIsFormateur(): ?bool
    {
        return $this->isFormateur;
    }

    public function setIsFormateur(?bool $isFormateur): self
    {
        $this->isFormateur = $isFormateur;

        return $this;
    }

    public function getIsLivreur(): ?bool
    {
        return $this->isLivreur;
    }

    public function setIsLivreur(?bool $isLivreur): self
    {
        $this->isLivreur = $isLivreur;

        return $this;
    }

    public function getIsVendeur(): ?bool
    {
        return $this->isVendeur;
    }

    public function setIsVendeur(?bool $isVendeur): self
    {
        $this->isVendeur = $isVendeur;

        return $this;
    }

    public function getCv(): ?int
    {
        return $this->cv;
    }

    public function setCv(?int $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getValidite(): ?bool
    {
        return $this->validite;
    }

    public function setValidite(bool $validite): self
    {
        $this->validite = $validite;

        return $this;
    }

    public function getMailconfirme(): ?bool
    {
        return $this->mailconfirme;
    }

    public function setMailconfirme(bool $mailconfirme): self
    {
        $this->mailconfirme = $mailconfirme;

        return $this;
    }

    public function getNumconfirme(): ?bool
    {
        return $this->numconfirme;
    }

    public function setNumconfirme(bool $numconfirme): self
    {
        $this->numconfirme = $numconfirme;

        return $this;
    }

    public function getAvertissement(): ?int
    {
        return $this->avertissement;
    }

    public function setAvertissement(int $avertissement): self
    {
        $this->avertissement = $avertissement;

        return $this;
    }


}
