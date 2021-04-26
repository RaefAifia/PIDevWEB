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

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="id_artiste")
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole(?string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse(string $adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return string
     */
    public function getNumTel(): string
    {
        return $this->numTel;
    }

    /**
     * @param string $numTel
     */
    public function setNumTel(string $numTel): void
    {
        $this->numTel = $numTel;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getBio(): ?string
    {
        return $this->bio;
    }

    /**
     * @param string|null $bio
     */
    public function setBio(?string $bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @return bool|null
     */
    public function getIsFormateur(): ?bool
    {
        return $this->isFormateur;
    }

    /**
     * @param bool|null $isFormateur
     */
    public function setIsFormateur(?bool $isFormateur): void
    {
        $this->isFormateur = $isFormateur;
    }

    /**
     * @return bool|null
     */
    public function getIsRecruteur(): ?bool
    {
        return $this->isRecruteur;
    }

    /**
     * @param bool|null $isRecruteur
     */
    public function setIsRecruteur(?bool $isRecruteur): void
    {
        $this->isRecruteur = $isRecruteur;
    }

    /**
     * @return bool|null
     */
    public function getIsVendeur(): ?bool
    {
        return $this->isVendeur;
    }

    /**
     * @param bool|null $isVendeur
     */
    public function setIsVendeur(?bool $isVendeur): void
    {
        $this->isVendeur = $isVendeur;
    }

    /**
     * @return int|null
     */
    public function getCv(): ?int
    {
        return $this->cv;
    }

    /**
     * @param int|null $cv
     */
    public function setCv(?int $cv): void
    {
        $this->cv = $cv;
    }

    /**
     * @return bool
     */
    public function isValidite(): bool
    {
        return $this->validite;
    }

    /**
     * @param bool $validite
     */
    public function setValidite(bool $validite): void
    {
        $this->validite = $validite;
    }

    /**
     * @return bool
     */
    public function isMailconfirme(): bool
    {
        return $this->mailconfirme;
    }

    /**
     * @param bool $mailconfirme
     */
    public function setMailconfirme(bool $mailconfirme): void
    {
        $this->mailconfirme = $mailconfirme;
    }

    /**
     * @return bool
     */
    public function isNumconfirme(): bool
    {
        return $this->numconfirme;
    }

    /**
     * @param bool $numconfirme
     */
    public function setNumconfirme(bool $numconfirme): void
    {
        $this->numconfirme = $numconfirme;
    }

    /**
     * @return int
     */
    public function getAvertissement(): int
    {
        return $this->avertissement;
    }

    /**
     * @param int $avertissement
     */
    public function setAvertissement(int $avertissement): void
    {
        $this->avertissement = $avertissement;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setIdArtiste($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getIdArtiste() === $this) {
                $evenement->setIdArtiste(null);
            }
        }

        return $this;
    }


}
