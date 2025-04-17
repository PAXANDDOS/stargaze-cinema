<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Hall;
use App\Enum\HallType;

class HallService extends AbstractEntityService
{
    /**
     * Creates new Hall from parameters or updates an existing by passing its entity.
     */
    public function create(array $params, Hall $hall = new Hall()): Hall
    {
        if (isset($params['name'])) {
            $hall->setName($params['name']);
        }
        if (isset($params['capacity'])) {
            $hall->setCapacity($params['capacity']);
        }
        if (isset($params['type'])) {
            $hall->setType(HallType::from($params['type']));
        }

        return $hall;
    }
}
