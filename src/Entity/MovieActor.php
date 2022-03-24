<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieActor
 *
 * @ORM\Table(name="bktest.movie_actor", indexes={@ORM\Index(name="IDX_15351AFF10DAF24A", columns={"actor_id"}), @ORM\Index(name="IDX_15351AFF8F93B6FC", columns={"movie_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MovieActorRepository")
 */
class MovieActor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bktest.movie_actor_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Actor
     *
     * @ORM\ManyToOne(targetEntity="Actor", inversedBy="bktest.actor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actor_id", referencedColumnName="id")
     * })
     */
    private $actor;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="Movie", inversedBy="bktest.movie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     * })
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): self
    {
        $this->actor = $actor;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }


}
