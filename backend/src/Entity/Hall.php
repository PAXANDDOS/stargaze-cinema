<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\HallType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: \App\Repository\HallRepository::class)]
#[ORM\Table(name: 'halls'), ORM\HasLifecycleCallbacks]
class Hall extends AbstractEntity
{
    #[ORM\Column(type: 'string', length: 16)]
    private string $name;

    #[ORM\Column(type: 'smallint')]
    private int $capacity;

    #[ORM\Column(type: 'string', enumType: HallType::class)]
    private HallType $type;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getType(): HallType
    {
        return $this->type;
    }

    public function setType(HallType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'type' => $this->type,
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.u'),
            'updated_at' => $this->updated_at->format('Y-m-d\TH:i:s.u'),
        ];
    }
}
