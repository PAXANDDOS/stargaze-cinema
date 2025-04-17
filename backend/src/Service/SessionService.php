<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Session;
use App\Exception\NotExistsException;

class SessionService extends AbstractEntityService
{
    /**
     * Creates new Session from parameters or updates an existing by passing its entity.
     *
     * @throws NotExistsException
     */
    public function create(array $params, Session $session = new Session()): Session
    {
        if (isset($params['beginAt'])) {
            $session->setBeginAt($params['beginAt']);
        }
        if (isset($params['endAt'])) {
            $session->setEndAt($params['endAt']);
        }
        if (isset($params['movieId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\Movie::class)->find($params['movieId'])) {
                throw new NotExistsException('Selected genre does not exist.');
            }
            $session->setMovie($entity);
        }
        if (isset($params['hallId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\Hall::class)->find($params['hallId'])) {
                throw new NotExistsException('Selected hall does not exist.');
            }
            $session->setHall($entity);
        }

        return $session;
    }
}
