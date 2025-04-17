<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Movie;
use App\Enum\PEGI;
use App\Exception\NotExistsException;

class MovieService extends AbstractEntityService
{
    /**
     * Creates new Movie from parameters or updates an existing by passing its entity.
     *
     * @throws NotExistsException
     */
    public function create(array $params, Movie $movie = new Movie()): Movie
    {
        if (isset($params['title'])) {
            $movie->setTitle($params['title']);
        }
        $movie->setSynopsis($params['synopsis'] ?? null);
        $movie->setPoster($params['poster'] ?? null);
        if (isset($params['price'])) {
            $movie->setPrice($params['price']);
        }
        if (isset($params['year'])) {
            $movie->setYear($params['year']);
        }
        if (isset($params['runtime'])) {
            $movie->setRuntime($params['runtime']);
        }
        if (isset($params['rating'])) {
            $movie->setRating(PEGI::from($params['rating']));
        }
        if (isset($params['languageId'])) {
            if (!$entity = $this->getEntityRepository(\App\Entity\Language::class)->find($params['languageId'])) {
                throw new NotExistsException('Selected language does not exist.');
            }
            $movie->setLanguage($entity);
        }
        if (isset($params['countryIds'])) {
            foreach ($params['countryIds'] as $id) {
                if (!$entity = $this->getEntityRepository(\App\Entity\Country::class)->find($id)) {
                    throw new NotExistsException("Selected country with ID $id does not exist.");
                }
                $movie->addCountry($entity);
            }
        }
        if (isset($params['genreIds'])) {
            foreach ($params['genreIds'] as $id) {
                if (!$entity = $this->getEntityRepository(\App\Entity\Genre::class)->find($id)) {
                    throw new NotExistsException("Selected genre with ID $id does not exist.");
                }
                $movie->addGenre($entity);
            }
        }
        if (isset($params['directorIds'])) {
            foreach ($params['directorIds'] as $id) {
                if (!$entity = $this->getEntityRepository(\App\Entity\Director::class)->find($id)) {
                    throw new NotExistsException("Selected director with ID $id does not exist.");
                }
                $movie->addDirector($entity);
            }
        }

        return $movie;
    }
}
