<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_forrec", columns={"formation_id"}), @ORM\Index(name="fk_userrec", columns={"user_id"}), @ORM\Index(name="fk_oeuvrec", columns={"oeuvrage_id"}), @ORM\Index(name="fk_eventrec", columns={"evenement_id"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="reclamation_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reclamationId;

    /**
     * @var string
     *
     * @ORM\Column(name="reclamation_nom", type="string", length=50, nullable=false)
     */
    private $reclamationNom;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="avertissement", type="boolean", nullable=false)
     */
    private $avertissement;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="evenement_id", referencedColumnName="evenement_id")
     * })
     */
    private $evenement;

    /**
     * @var \Formation
     *
     * @ORM\ManyToOne(targetEntity="Formation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="formation_id", referencedColumnName="formation_id")
     * })
     */
    private $formation;

    /**
     * @var \Oeuvrage
     *
     * @ORM\ManyToOne(targetEntity="Oeuvrage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvrage_id", referencedColumnName="oeuvrage_id")
     * })
     */
    private $oeuvrage;

    public function getReclamationId(): ?int
    {
        return $this->reclamationId;
    }

    public function getReclamationNom(): ?string
    {
        return $this->reclamationNom;
    }

    public function setReclamationNom(string $reclamationNom): self
    {
        $this->reclamationNom = $reclamationNom;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAvertissement(): ?bool
    {
        return $this->avertissement;
    }

    public function setAvertissement(bool $avertissement): self
    {
        $this->avertissement = $avertissement;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getOeuvrage(): ?Oeuvrage
    {
        return $this->oeuvrage;
    }

    public function setOeuvrage(?Oeuvrage $oeuvrage): self
    {
        $this->oeuvrage = $oeuvrage;

        return $this;
    }


}
