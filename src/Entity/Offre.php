<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OffreRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Offre
 *
 * @ORM\Table(name="offre", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @var int
     *
     * @ORM\Column(name="offre_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $offreId;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez donner un nom à cette offre !")
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez donner une description à cette offre !")
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var int
     * @Assert\NotBlank(message="Veuillez saisir le nombre des profiteur de cette offre !")
     * @Assert\Positive(message=" le nombre des pofiteur doit être positif !")
     * @ORM\Column(name="nb_client", type="integer", nullable=false)
     */
    private $nbClient;

    /**
     * @var int
     *
     * @ORM\Column(name="is_valid", type="integer", nullable=false)
     */
    private $isValid = '0';

    /**
     * @var \DateTime

     * @Assert\GreaterThan("today", message ="La dade de début ne devrait pas être antérieure à la date du jour ")
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getOffreId(): ?int
    {
        return $this->offreId;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbClient(): ?int
    {
        return $this->nbClient;
    }

    public function setNbClient(int $nbClient): self
    {
        $this->nbClient = $nbClient;

        return $this;
    }

    public function getIsValid(): ?int
    {
        return $this->isValid;
    }

    public function setIsValid(int $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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


}
