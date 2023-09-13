<?php

declare(strict_types=1);

use Codeception\Example;
use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

class CreateNewGameCest
{
    public function createGame(ApiTester $apiTester): void
    {
        $apiTester->wantToTest('Create new game');

        $requestBody = [
            'name' => 'The Witcher',
            'data' => [
                'year' => 2010,
                'genre' => 'action',
                'price' => '19.99'
            ]
        ];

        $apiTester->sendPostAsJson('', $requestBody);

        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $apiTester->seeResponseIsJson();

        $apiTester->seeResponseMatchesJsonType(
            [
                'id' => 'string',
                'name' => 'string',
                'data' => [
                    'year' => 'integer',
                    'genre' => 'string',
                    'price' => 'string'
                ]
            ]
        );
    }

    /** @dataProvider incorrectDataProvider */
    public function createGameWithIncorrectData(ApiTester $apiTester, Example $provider): void
    {
        $apiTester->wantToTest('Create new game with incorrect data');

        $apiTester->sendPostAsJson('', $provider['requestBody']);

        $apiTester->seeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    private function incorrectDataProvider(): iterable
    {
        yield [
            'requestBody' => [],
        ];

        yield [
          'requestBody' => '-1'
        ];
    }
}
