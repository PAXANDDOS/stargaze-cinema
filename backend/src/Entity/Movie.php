<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PEGI;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: \App\Repository\MovieRepository::class)]
#[ORM\Table(name: 'movies'), ORM\HasLifecycleCallbacks, Gedmo\SoftDeleteable]
class Movie extends AbstractEntity
{
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'string', length: 64)]
    private string $title;

    #[ORM\Column(type: 'string', length: 65535, nullable: true)]
    private ?string $synopsis;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $poster;

    #[ORM\Column(type: 'decimal')]
    private float $price;

    #[ORM\Column(type: 'smallint')]
    private int $year;

    #[ORM\Column(type: 'smallint')]
    private int $runtime;

    #[ORM\Column(type: 'string', enumType: PEGI::class)]
    private PEGI $rating;

    #[ORM\ManyToOne(targetEntity: Language::class, inversedBy: 'movies')]
    #[ORM\JoinColumn(nullable: false)]
    private Language $language;

    #[ORM\ManyToMany(targetEntity: Country::class, mappedBy: 'movies')]
    private Collection $countries;

    #[ORM\ManyToMany(targetEntity: Genre::class, mappedBy: 'movies')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Director::class, mappedBy: 'movies')]
    private Collection $directors;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Session::class)]
    private Collection $sessions;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Frame::class)]
    private Collection $frames;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->frames = new ArrayCollection();
        $this->countries = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->directors = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getRuntime(): int
    {
        return $this->runtime;
    }

    public function setRuntime(int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getRating(): PEGI
    {
        return $this->rating;
    }

    public function setRating(PEGI $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries[] = $country;
            $country->addMovie($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->countries->removeElement($country)) {
            $country->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Director>
     */
    public function getDirectors(): Collection
    {
        return $this->directors;
    }

    public function addDirector(Director $director): self
    {
        if (!$this->directors->contains($director)) {
            $this->directors[] = $director;
            $director->addMovie($this);
        }

        return $this;
    }

    public function removeDirector(Director $director): self
    {
        if ($this->directors->removeElement($director)) {
            $director->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->session[] = $session;
            $session->setMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Frame>
     */
    public function getFrames(): Collection
    {
        return $this->frames;
    }

    public function addFrame(Frame $frame): self
    {
        if (!$this->frames->contains($frame)) {
            $this->frames[] = $frame;
            $frame->setMovie($this);
        }

        return $this;
    }

    public function removeFrame(Frame $frame): self
    {
        if ($this->frames->removeElement($frame)) {
            if ($frame->getMovie() === $this) {
                $frame->setMovie(null);
            }
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'synopsis' => $this->synopsis,
            'poster' => $this->poster,
            'price' => $this->price,
            'year' => $this->year,
            'runtime' => $this->runtime,
            'rating' => $this->rating,
            'language' => [
                'id' => $this->language->getId(),
                'name' => $this->language->getName(),
                'created_at' => $this->language->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                'updated_at' => $this->language->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
            ],
            'countries' => $this->countries->map(function (Country $country) {
                return [
                    'id' => $country->getId(),
                    'name' => $country->getName(),
                    'created_at' => $country->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $country->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ];
            })->toArray(),
            'genres' => $this->genres->map(function (Genre $genre) {
                return [
                    'id' => $genre->getId(),
                    'name' => $genre->getName(),
                    'created_at' => $genre->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $genre->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ];
            })->toArray(),
            'directors' => $this->directors->map(function (Director $director) {
                return [
                    'id' => $director->getId(),
                    'name' => $director->getName(),
                    'created_at' => $director->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $director->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ];
            })->toArray(),
            'sessions' => $this->sessions->map(function (Session $session) {
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
            'frames' => $this->frames->map(function (Frame $frame) {
                return [
                    'id' => $frame->getId(),
                    'image' => $frame->getImage(),
                    'created_at' => $frame->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $frame->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ];
            })->toArray(),
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
        ];
    }
}
