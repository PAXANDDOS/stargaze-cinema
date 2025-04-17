<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Frame;
use App\Entity\Movie;
use App\Tests\ApiTester;

class FramesCest
{
    public function tryToGetFrames(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Frame::class, [
            'image' => 'http://localhost:9000/stargazecinema/frames/1.png',
            'movie' => $I->grabEntityFromRepository(Movie::class, ['id' => 1]),
        ]);

        $I->sendGet('/frames');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'image' => 'http://localhost:9000/stargazecinema/frames/1.png',
            ],
        ]);
    }

    public function tryToGetInvalidFrame(ApiTester $I): void
    {
        $I->sendGet('/frames/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostFrame(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost(
            '/frames',
            ['movie_id' => 3],
            [
                'image' => [
                    'name' => 'image_1.jpg',
                    'type' => 'image/jpeg',
                    'error' => UPLOAD_ERR_OK,
                    'size' => filesize(codecept_data_dir('image_1.jpg')),
                    'tmp_name' => codecept_data_dir('image_1.jpg'),
                ],
            ]
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToDeleteFrame(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Frame::class, [
            'image' => 'http://localhost:9000/stargazecinema/frames/1.png',
            'movie' => $I->grabEntityFromRepository(Movie::class, ['id' => 1]),
        ]);
        $id = $I->grabFromRepository(
            Frame::class,
            'id',
            ['image' => 'http://localhost:9000/stargazecinema/frames/1.png']
        );
        $I->sendDelete("/frames/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
