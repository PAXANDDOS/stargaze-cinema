<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

#[ORM\Entity(repositoryClass: \App\Repository\TicketRepository::class)]
#[ORM\Table(name: 'tickets'), ORM\HasLifecycleCallbacks, Gedmo\SoftDeleteable]
class Ticket extends AbstractEntity
{
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'smallint')]
    private int $place;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Session::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Session $session;

    public function getPlace(): int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function setSession(Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function jsonSerialize(): array
    {
        $sessionMovie = $this->session->getMovie();
        $sessionHall = $this->session->getHall();

        return [
            'id' => $this->id,
            'place' => $this->place,
            'user' => [
                'id' => $this->user->getId(),
                'name' => $this->user->getName(),
                'email' => $this->user->getEmail(),
                'roles' => $this->user->getRoles(),
                'created_at' => $this->user->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                'updated_at' => $this->user->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
            ],
            'session' => [
                'id' => $this->session->getId(),
                'begin_at' => $this->session->getBeginAt()->format('Y-m-d\TH:i:s.u'),
                'end_at' => $this->session->getEndAt()->format('Y-m-d\TH:i:s.u'),
                'movie' => [
                    'id' => $sessionMovie->getId(),
                    'title' => $sessionMovie->getTitle(),
                    'synopsis' => $sessionMovie->getSynopsis(),
                    'poster' => $sessionMovie->getPoster(),
                    'price' => $sessionMovie->getPrice(),
                    'year' => $sessionMovie->getYear(),
                    'runtime' => $sessionMovie->getRuntime(),
                    'rating' => $sessionMovie->getRating(),
                    'language' => [
                        'id' => $sessionMovie->getLanguage()->getId(),
                        'name' => $sessionMovie->getLanguage()->getName(),
                        'created_at' => $sessionMovie->getLanguage()->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                        'updated_at' => $sessionMovie->getLanguage()->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                    ],
                    'countries' => $sessionMovie->getCountries()->map(function (Country $country) {
                        return [
                            'id' => $country->getId(),
                            'name' => $country->getName(),
                            'created_at' => $country->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                            'updated_at' => $country->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                        ];
                    })->toArray(),
                    'genres' => $sessionMovie->getGenres()->map(function (Genre $genre) {
                        return [
                            'id' => $genre->getId(),
                            'name' => $genre->getName(),
                            'created_at' => $genre->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                            'updated_at' => $genre->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                        ];
                    })->toArray(),
                    'directors' => $sessionMovie->getDirectors()->map(function (Director $director) {
                        return [
                            'id' => $director->getId(),
                            'name' => $director->getName(),
                            'created_at' => $director->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                            'updated_at' => $director->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                        ];
                    })->toArray(),
                    'sessions' => $sessionMovie->getSessions()->map(function (Session $session) {
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
                    'frames' => $sessionMovie->getFrames()->map(function (Frame $frame) {
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
                    'id' => $sessionHall->getId(),
                    'name' => $sessionHall->getName(),
                    'capacity' => $sessionHall->getCapacity(),
                    'type' => $sessionHall->getType(),
                    'created_at' => $sessionHall->getCreatedAt()->format('Y-m-d\TH:i:s.u'),
                    'updated_at' => $sessionHall->getUpdatedAt()->format('Y-m-d\TH:i:s.u'),
                ],
            ],
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
        ];
    }
}
