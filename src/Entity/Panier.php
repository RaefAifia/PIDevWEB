<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", uniqueConstraints={@ORM\UniqueConstraint(name="commande_id", columns={"commande_id", "oeuvrage_id"})}, indexes={@ORM\Index(name="oeuvrage_id", columns={"oeuvrage_id"}), @ORM\Index(name="IDX_24CC0DF282EA2E54", columns={"commande_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commande_id", referencedColumnName="commande_id")
     * })
     */
    private $commande;

    /**
     * @var \Oeuvrage
     *
     * @ORM\ManyToOne(targetEntity="Oeuvrage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvrage_id", referencedColumnName="oeuvrage_id")
     * })
     */
    private $oeuvrage;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

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
