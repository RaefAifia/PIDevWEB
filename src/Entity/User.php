<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
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
     * @ORM\Column(name="role", type="string", length=50, nullable=true)
     */
    private $role;

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
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string|null
     *
     * @ORM\Column(name="bio", type="string", length=255, nullable=true)
     */
    private $bio;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_formateur", type="boolean", nullable=true)
     */
    private $isFormateur;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_recruteur", type="boolean", nullable=true)
     */
    private $isRecruteur;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_vendeur", type="boolean", nullable=true)
     */
    private $isVendeur;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cv", type="integer", nullable=true)
     */
    private $cv;

    /**
     * @var bool
     *
     * @ORM\Column(name="validité", type="boolean", nullable=false)
     */
    private $validit�;

    /**
     * @var bool
     *
     * @ORM\Column(name="mailconfirmé", type="boolean", nullable=false)
     */
    private $mailconfirm�;

    /**
     * @var bool
     *
     * @ORM\Column(name="numconfirmé", type="boolean", nullable=false)
     */
    private $numconfirm�;

    /**
     * @var int
     *
     * @ORM\Column(name="avertissement", type="integer", nullable=false)
     */
    private $avertissement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Oeuvrage", inversedBy="user")
     * @ORM\JoinTable(name="panier_temp",
     *   joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="oeuvrage_id", referencedColumnName="oeuvrage_id")
     *   }
     * )
     */
    private $oeuvrage;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->oeuvrage = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(): String
    {
        // TODO: Implement __toString() method.
        return $this->userId;
    }

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

    public function getIsRecruteur(): ?bool
    {
        return $this->isRecruteur;
    }

    public function setIsRecruteur(?bool $isRecruteur): self
    {
        $this->isRecruteur = $isRecruteur;

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

    public function getValidit�(): ?bool
    {
        return $this->validit�;
    }

    public function setValidit�(bool $validit�): self
    {
        $this->validit� = $validit�;

        return $this;
    }

    public function getMailconfirm�(): ?bool
    {
        return $this->mailconfirm�;
    }

    public function setMailconfirm�(bool $mailconfirm�): self
    {
        $this->mailconfirm� = $mailconfirm�;

        return $this;
    }

    public function getNumconfirm�(): ?bool
    {
        return $this->numconfirm�;
    }

    public function setNumconfirm�(bool $numconfirm�): self
    {
        $this->numconfirm� = $numconfirm�;

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

    /**
     * @return Collection|Oeuvrage[]
     */
    public function getOeuvrage(): Collection
    {
        return $this->oeuvrage;
    }

    public function addOeuvrage(Oeuvrage $oeuvrage): self
    {
        if (!$this->oeuvrage->contains($oeuvrage)) {
            $this->oeuvrage[] = $oeuvrage;
        }

        return $this;
    }

    public function removeOeuvrage(Oeuvrage $oeuvrage): self
    {
        $this->oeuvrage->removeElement($oeuvrage);

        return $this;
    }


}
