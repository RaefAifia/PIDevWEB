<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * PanierTemp
 *
 * @ORM\Table(name="panier_temp", uniqueConstraints={@ORM\UniqueConstraint(name="oeuvrage_id", columns={"oeuvrage_id", "user_id"})}, indexes={@ORM\Index(name="user_id", columns={"user_id"}), @ORM\Index(name="IDX_FFB5A0B349DBAF18", columns={"oeuvrage_id"})})
 * @ORM\Entity
 */
class PanierTemp
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
     * @Assert\NotBlank(message="Veuillez saisir la quantite")
     * @Assert\Positive(message="Veuillez saisir une quantite positive")
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \User

     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
