<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Ticket;
use App\Exception\NotExistsException;

class TicketService extends AbstractEntityService
{
    /**
     * Creates new Ticket from parameters or updates an existing by passing its entity.
     *
     * @throws NotExistsException
     */
    public function create(array $params, Ticket $ticket = new Ticket()): Ticket
    {
        if (isset($params['place'])) {
            $ticket->setPlace($params['place']);
        }
        if (isset($params['userId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\User::class)->find($params['userId'])) {
                throw new NotExistsException('Selected user does not exist.');
            }
            $ticket->setUser($entity);
        }
        if (isset($params['sessionId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\Session::class)->find($params['sessionId'])) {
                throw new NotExistsException('Selected session does not exist.');
            }
            $ticket->setSession($entity);
        }

        return $ticket;
    }
}
