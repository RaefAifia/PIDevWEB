<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relations
 *
 * @ORM\Table(name="relations", indexes={@ORM\Index(name="fk_followee", columns={"followee_id"})})
 * @ORM\Entity
 */
class Relations
{
    /**
     * @var int
     *
     * @ORM\Column(name="follower_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $followerId;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="followee_id", referencedColumnName="user_id")
     * })
     */
    private $followee;

    public function getFollowerId(): ?int
    {
        return $this->followerId;
    }

    public function getFollowee(): ?User
    {
        return $this->followee;
    }

    public function setFollowee(?User $followee): self
    {
        $this->followee = $followee;

        return $this;
    }


}
