<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use Codeception\Util\HttpCode;
use Tests\Support\ApiTester;

class UpdateGameInfoCest
{
    private const GAME_NAME = 'New Test Game';

    private string $gameId;

    #[Before('UpdateGameInfo')]
    public function precondition(ApiTester $apiTester): void
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

    public function updateGameInfo(ApiTester $apiTester): void
    {
        $apiTester->wantToTest('Update game info');

        $updateRequest = [
            'name' => self::GAME_NAME
        ];

        $apiTester->sendPatchAsJson($this->gameId, $updateRequest);

        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $apiTester->seeResponseContainsJson(
            [
                'id' => $this->gameId,
                'name' => self::GAME_NAME
            ]
        );
    }

    #[After('UpdateGameInfo')]
    public function postcondition(ApiTester $apiTester): void
    {
        $apiTester->sendDelete($this->gameId);

        $apiTester->seeResponseCodeIs(HttpCode::OK);
    }
}
