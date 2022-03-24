<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MovieDirector
 *
 * @ORM\Table(name="bktest.movie_director", indexes={@ORM\Index(name="IDX_300DEE8A8F93B6FC", columns={"movie_id"}), @ORM\Index(name="IDX_300DEE8A899FB366", columns={"director_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\MovieDirectorRepository")
 */
class MovieDirector
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="bktest.movie_director_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="Movie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="movie_id", referencedColumnName="id")
     * })
     */
    private $movie;

    /**
     * @var Director
     *
     * @ORM\ManyToOne(targetEntity="Director")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="director_id", referencedColumnName="id")
     * })
     */
    private $director;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(?Director $director): self
    {
        $this->director = $director;

        return $this;
    }


}
