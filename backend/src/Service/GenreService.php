<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Genre;

class GenreService extends AbstractEntityService
{
    /**
     * Creates new Genre from parameters or updates an existing by passing its entity.
     */
    public function create(array $params, Genre $genre = new Genre()): Genre
    {
        if (isset($params['name'])) {
            $genre->setName($params['name']);
        }

        return $genre;
    }
}
