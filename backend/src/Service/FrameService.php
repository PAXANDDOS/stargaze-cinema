<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Frame;
use App\Exception\NotExistsException;

class FrameService extends AbstractEntityService
{
    /**
     * Creates new Frame from parameters or updates an existing by passing its entity.
     *
     * @throws NotExistsException
     */
    public function create(array $params, Frame $frame = new Frame()): Frame
    {
        if (isset($params['image'])) {
            $frame->setImage($params['image']);
        }
        if (isset($params['movieId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\Movie::class)->find($params['movieId'])) {
                throw new NotExistsException('Selected movie does not exist.');
            }
            $frame->setMovie($entity);
        }

        return $frame;
    }
}
