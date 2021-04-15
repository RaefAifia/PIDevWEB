<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OeuvrageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Oeuvrage
 * @Vich\Uploadable
 * @ORM\Table(name="oeuvrage", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass=OeuvrageRepository::class)
 * @UniqueEntity(fields= {"nom"}, message =" l'oeuvre existe déjà !  ")
 */

class Oeuvrage
{
    /**
     * @var int
     *
     * @ORM\Column(name="oeuvrage_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $oeuvrageId;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez donner un nom à votre oeuvre !")
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez choisir le domaine de votre oeuvre !")
     *
     * @ORM\Column(name="domaine", type="string", length=50, nullable=false)
     */
    private $domaine;

    /**
     * @var float
     * @Assert\NotBlank(message="Veuillez saisir le prix de votre oeuvre !")
     * @Assert\Positive(message=" le prix de votre oeuvre doit être positif !")
     * @Assert\Type(
     *     type="float",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     *
     */
    private $prix;

    /**
     * @var int
     *@Assert\NotBlank(message="Veuillez saisir la quantité de votre oeuvre !")
     * @Assert\Type(
     *     type="integer",
     *     message=" {{ value }} n'est pas un {{ type }} valide."
     * )
     * @Assert\Positive(message=" la quantité de votre oeuvre doit être positive !")
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var string
     *  *@Assert\NotBlank(message="Veuillez donner une description à votre oeuvre !")
     * @ORM\Column(name="description", type="string", length=100, nullable=false)
     *  @Assert\Length(
     *      min = 11,
     *
     *      minMessage = "Veuillez décrire mieux votre oeuvre , la description doit avoir au moins {{ limit }} caractere",
     *
     * )
     *
     */
    private $description;

    /**
     * @var string

     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

   /**
     * @var int
     *
     * @ORM\Column(name="isvalid", type="integer", nullable=false)
     */
    private $isvalid = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="user_id", referencedColumnName="user_id",)
     * })
     */
    private $user;

    public function getOeuvrageId(): ?int
    {
        return $this->oeuvrageId;
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

    public function getDomaine(): ?string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }


    public function getIsvalid(): ?int
    {
        return $this->isvalid;
    }

    public function setIsvalid(int $isvalid): self
    {
        $this->isvalid = $isvalid;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;


    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

}
