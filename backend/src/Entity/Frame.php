<?php

namespace App\Entity;

use App\Repository\FrameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FrameRepository::class)]
#[ORM\Table(name: 'frames'), ORM\HasLifecycleCallbacks]
class Frame extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $image;

    #[ORM\ManyToOne(targetEntity: Movie::class, inversedBy: 'frames')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Movie $movie;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function jsonSerialize(): array
    {
        $movie = $this->getMovie();

        return [
            'id' => $this->id,
            'image' => $this->image,
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
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
        ];
    }
}
