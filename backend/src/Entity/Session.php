<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: \App\Repository\SessionRepository::class)]
#[ORM\Table(name: 'sessions'), ORM\HasLifecycleCallbacks, Gedmo\SoftDeleteable]
class Session extends AbstractEntity
{
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $begin_at;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $end_at;

    #[ORM\ManyToOne(targetEntity: Movie::class, inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private Movie $movie;

    #[ORM\ManyToOne(targetEntity: Hall::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Hall $hall;

    public function getBeginAt(): \DateTime
    {
        return $this->begin_at;
    }

    public function setBeginAt(\DateTime $begin_at): self
    {
        $this->begin_at = $begin_at;

        return $this;
    }

    public function getEndAt(): \DateTime
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTime $end_at): self
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getMovie(): Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getHall(): Hall
    {
        return $this->hall;
    }

    public function setHall(Hall $hall): self
    {
        $this->hall = $hall;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $movie = $this->getMovie();

        return [
            'id' => $this->id,
            'begin_at' => $this->begin_at->format('Y-m-d\TH:i:s.u'),
            'end_at' => $this->end_at->format('Y-m-d\TH:i:s.u'),
            'movie' => [
                'id' => $movie->getId(),
                'title' => $movie->getTitle(),
                'synopsis' => $movie->getSynopsis(),
                'poster' => $movie->getPoster(),
                'price' => $movie->getPrice(),
                'year' => $movie->getYear(),
                'runtime' => $movie->getRuntime(),
                'rating' => $movie->getRating(),
                'language' => [
                    'id' => $movie->getLanguage()->getId(),
                    'name' => $movie->getLanguage()->getName(),
                    'created_at' => $movie->getLanguage()->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $movie->getLanguage()->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ],
                'countries' => $movie->getCountries()->map(function (Country $country) {
                    return [
                        'id' => $country->getId(),
                        'name' => $country->getName(),
                        'created_at' => $country->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $country->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                    ];
                })->toArray(),
                'genres' => $movie->getGenres()->map(function (Genre $genre) {
                    return [
                        'id' => $genre->getId(),
                        'name' => $genre->getName(),
                        'created_at' => $genre->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $genre->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                    ];
                })->toArray(),
                'directors' => $movie->getDirectors()->map(function (Director $director) {
                    return [
                        'id' => $director->getId(),
                        'name' => $director->getName(),
                        'created_at' => $director->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $director->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                    ];
                })->toArray(),
                'sessions' => $movie->getSessions()->map(function (Session $session) {
                    return [
                        'id' => $session->getId(),
                        'begin_at' => $session->getBeginAt()->format('Y-m-d\TH:i:s.u'),
                        'end_at' => $session->getEndAt()->format('Y-m-d\TH:i:s.u'),
                        'hall' => [
                            'id' => $session->getHall()->getId(),
                            'name' => $session->getHall()->getName(),
                            'capacity' => $session->getHall()->getCapacity(),
                            'type' => $session->getHall()->getType(),
                            'created_at' => $session->getHall()->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                            'updated_at' => $session->getHall()->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                        ],
                        'created_at' => $session->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $session->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                        'deleted_at' => $session->getDeletedAt()?->format('Y-m-d\TH:i:s.u'),
                    ];
                })->toArray(),
                'frames' => $movie->getFrames()->map(function (Frame $frame) {
                    return [
                        'id' => $frame->getId(),
                        'image' => $frame->getImage(),
                        'created_at' => $frame->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $frame->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                    ];
                })->toArray(),
                'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
                'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
            ],
            'hall' => [
                'id' => $this->hall->getId(),
                'name' => $this->hall->getName(),
                'capacity' => $this->hall->getCapacity(),
                'type' => $this->hall->getType(),
                'created_at' => $this->hall->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                'updated_at' => $this->hall->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
            ],
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
        ];
    }
}
