<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_usercom", columns={"user_id"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="commande_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $commandeId;

    /**
     * @var float
     *
     * @ORM\Column(name="PrixTot", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixtot;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
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

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Oeuvrage", inversedBy="commande")
     * @ORM\JoinTable(name="panier",
     *   joinColumns={
     *     @ORM\JoinColumn(name="commande_id", referencedColumnName="commande_id")
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

    public function getCommandeId(): ?int
    {
        return $this->commandeId;
    }

    public function getPrixtot(): ?float
    {
        return $this->prixtot;
    }

    public function setPrixtot(float $prixtot): self
    {
        $this->prixtot = $prixtot;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
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
