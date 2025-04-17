<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Director;

class DirectorService extends AbstractEntityService
{
    /**
     * Creates new Director from parameters or updates an existing by passing its entity.
     *
     * @throws NotExistsException
     */
    public function create(array $params, Director $director = new Director()): Director
    {
        if (isset($params['name'])) {
            $director->setName($params['name']);
        }

        return $director;
    }
}
