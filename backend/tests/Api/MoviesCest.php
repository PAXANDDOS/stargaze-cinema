<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Country;
use App\Entity\Director;
use App\Entity\Genre;
use App\Entity\Language;
use App\Entity\Movie;
use App\Enum\PEGI;
use App\Tests\ApiTester;
use Doctrine\Common\Collections\ArrayCollection;

class MoviesCest
{
    public function tryToGetMovie(ApiTester $I): void
    {
        $I->haveInRepository(Movie::class, [
            'genres' => new ArrayCollection([$I->grabEntityFromRepository(Genre::class, ['id' => 1])]),
            'directors' => new ArrayCollection([$I->grabEntityFromRepository(Director::class, ['id' => 1])]),
            'countries' => new ArrayCollection([$I->grabEntityFromRepository(Country::class, ['id' => 1])]),
            'language' => $I->grabEntityFromRepository(Language::class, ['id' => 1]),
            'title' => 'Raya And The Last Dragon',
            'synopsis' => 'Some text.',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 120,
            'rating' => PEGI::PG3,
        ]);

        $I->sendGet('/movies');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'title' => 'Raya And The Last Dragon',
            ],
        ]);
    }

    public function tryToGetInvalidMovie(ApiTester $I): void
    {
        $I->sendGet('/movies/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostMovie(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/movies', [
            'genre_ids' => '[1,2]',
            'director_ids' => '[1,2]',
            'country_ids' => '[1,2]',
            'language_id' => 3,
            'title' => 'Raya And The Last Dragon',
            'synopsis' => 'Some text.',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 120,
            'rating' => 'PEGI 3',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidMovie(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/movies', [
            'genre_id' => 'Cartoon',
            'director_id' => 'Somebody',
            'title' => 'Raya And The Last Dragon',
            'synopsis' => 'Some text.',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 'long',
            'country' => 'United States',
            'language' => 'English',
            'rating' => 'PEGI 3',
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToUploadFrames(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->deleteHeader('Content-Type');
        $I->haveInRepository(Movie::class, [
            'genres' => new ArrayCollection([$I->grabEntityFromRepository(Genre::class, ['id' => 1])]),
            'directors' => new ArrayCollection([$I->grabEntityFromRepository(Director::class, ['id' => 1])]),
            'countries' => new ArrayCollection([$I->grabEntityFromRepository(Country::class, ['id' => 1])]),
            'language' => $I->grabEntityFromRepository(Language::class, ['id' => 1]),
            'title' => 'Raya And The Last Dragon',
            'synopsis' => 'Some text.',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 120,
            'rating' => PEGI::PG3,
        ]);
        $id = $I->grabFromRepository(Movie::class, 'id', ['title' => 'Raya And The Last Dragon']);

        $files = [
            'image_1' => codecept_data_dir('image_1.jpg'),
            'image_2' => codecept_data_dir('image_2.jpg'),
            'image_3' => codecept_data_dir('image_3.png'),
        ];

        $I->sendPost("/movies/$id/frames", [], $files);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPatchMovie(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Movie::class, [
            'genres' => new ArrayCollection([$I->grabEntityFromRepository(Genre::class, ['id' => 1])]),
            'directors' => new ArrayCollection([$I->grabEntityFromRepository(Director::class, ['id' => 1])]),
            'countries' => new ArrayCollection([$I->grabEntityFromRepository(Country::class, ['id' => 1])]),
            'language' => $I->grabEntityFromRepository(Language::class, ['id' => 1]),
            'title' => 'Raya And The Last Dragon',
            'synopsis' => 'Some text.',
            'poster' => 'https://lol.com/com.png',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 120,
            'rating' => PEGI::PG3,
        ]);
        $id = $I->grabFromRepository(Movie::class, 'id', ['title' => 'Raya And The Last Dragon']);
        $I->sendPatch("/movies/$id", [
            'synopsis' => 'Updated text.',
            'poster' => 'https://lol.com/com.png',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToDeleteMovie(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Movie::class, [
            'genres' => new ArrayCollection([$I->grabEntityFromRepository(Genre::class, ['id' => 1])]),
            'directors' => new ArrayCollection([$I->grabEntityFromRepository(Director::class, ['id' => 1])]),
            'countries' => new ArrayCollection([$I->grabEntityFromRepository(Country::class, ['id' => 1])]),
            'language' => $I->grabEntityFromRepository(Language::class, ['id' => 1]),
            'title' => 'Delete me',
            'synopsis' => 'Some text.',
            'price' => 10.00,
            'year' => 2021,
            'runtime' => 120,
            'rating' => PEGI::PG3,
        ]);
        $id = $I->grabFromRepository(Movie::class, 'id', ['title' => 'Delete me']);
        $I->sendDelete("/movies/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
