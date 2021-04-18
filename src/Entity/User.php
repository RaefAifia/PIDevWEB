<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * User
 *
 * @ORM\Entity

 * @ORM\Table(name="user")
 *
 */
/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

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
     * @Assert\NotBlank
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
     * @ORM\Column(type="json")
     */
    private $roles = [];

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
     * @Assert\Type("numeric")
     * @ORM\Column(name="num_tel", type="string", length=50, nullable=false)
     */
    private $numTel;

    /**
     * @var string
     *  @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
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
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

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
    private $isFormateur = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_recruteur", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $isRecruteur = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_vendeur", type="boolean", nullable=true, options={"default"="NULL"})
     */
    private $isVendeur = '0';

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
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost

    }

    public function getImageFile()
    {
        return $this->imageFile;
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


    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles() : array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(json $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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

    public function __toString()
    {
        return $this->email;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }


}
