<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RelationsRepository;

/**
 * Relations
 *
 * @ORM\Table(name="relations", indexes={@ORM\Index(name="followee_id", columns={"followee_id"})})
 * @ORM\Entity
 */
/**
 * @ORM\Entity(repositoryClass="App\Repository\RelationsRepository")
 */
class Relations
{
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="followee_id", referencedColumnName="user_id")
     * })
     */
    private $followee;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follower_id", referencedColumnName="user_id")
     * })
     */
    private $follower;

    public function getFollowee(): ?User
    {
        return $this->followee;
    }

    public function setFollowee(?User $followee): self
    {
        $this->followee = $followee;

        return $this;
    }

    public function getFollower(): ?User
    {
        return $this->follower;
    }

    public function setFollower(?User $follower): self
    {
        $this->follower = $follower;

        return $this;
    }


}
