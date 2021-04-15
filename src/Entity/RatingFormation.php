<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingFormation
 *
 * @ORM\Table(name="rating_formation", indexes={@ORM\Index(name="fk_ratefor", columns={"formation_id"}), @ORM\Index(name="fk_rateforuser", columns={"user_id"})})
 * @ORM\Entity
 */
class RatingFormation
{
    /**
     * @var int
     *
     * @ORM\Column(name="ratingf_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ratingfId;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float", precision=10, scale=0, nullable=false)
     */
    private $value;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="user_id")
     * })
     */
    private $user;

    public function getRatingfId(): ?int
    {
        return $this->ratingfId;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

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
