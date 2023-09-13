<?php

declare(strict_types=1);

use Codeception\Util\HttpCode;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Tests\Support\ApiTester;

class GetGameByIdCest
{
    private string $gameId;

    #[Before('GetGameById')]
    public function precodition(ApiTester $apiTester): void
    {
        $requestBody = [
            'name' => 'Test game',
            'data' => [
                'year' => 2005,
                'genre' => 'MMORPG',
                'price' => '23.99'
            ]
        ];

        $response = $apiTester->sendPostAsJson('', $requestBody);

        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $this->gameId = $response['id'];
    }

    public function getGameById(ApiTester $apiTester): void
    {
        $apiTester->wantToTest('Get game by id');

        $apiTester->sendGet($this->gameId);

        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $apiTester->seeResponseIsJson();

        $apiTester->seeResponseContainsJson(
            [
                'id' => $this->gameId,
                'name' => 'Test game',
                'data' => [
                    'year' => '2005',
                    'genre' => 'MMORPG',
                    'price' => '23.99'
                ]
            ]
        );
    }

    #[After('GetGameById')]
    public function postcondition(ApiTester $apiTester): void
    {
        $apiTester->sendDelete($this->gameId);

        $apiTester->seeResponseCodeIs(HttpCode::OK);
    }
}
