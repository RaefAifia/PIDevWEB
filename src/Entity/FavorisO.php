<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FavorisO
 *
 * @ORM\Table(name="favoris_o", indexes={@ORM\Index(name="fk_favouv", columns={"oeuvrage_id"}), @ORM\Index(name="fk_favo", columns={"user_id"})})
 * @ORM\Entity
 */
class FavorisO
{
    /**
     * @var int
     *
     * @ORM\Column(name="favoris_o_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $favorisOId;

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
     * @var \Oeuvrage
     *
     * @ORM\ManyToOne(targetEntity="Oeuvrage")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvrage_id", referencedColumnName="oeuvrage_id")
     * })
     */
    private $oeuvrage;

    public function getFavorisOId(): ?int
    {
        return $this->favorisOId;
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
