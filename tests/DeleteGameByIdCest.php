<?php

declare(strict_types=1);

use Codeception\Example;
use PHPUnit\Framework\Attributes\Before;
use Codeception\Util\HttpCode;
use PHPUnit\Framework\Assert;
use Tests\Support\ApiTester;

class DeleteGameByIdCest
{
    private string $gameId;

    #[Before('DeleteGameById')]
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

        print_r($this->gameId);
    }

    public function deleteGameById(ApiTester $apiTester): void
    {
        $apiTester->wantToTest('Delete game by id');

        $response = $apiTester->sendDelete($this->gameId);

        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $responseEntity = json_decode($response, true);

        $message = $responseEntity['message'];

        Assert::assertEquals(
            expected: "Object with id = " . $this->gameId . " has been deleted.",
            actual: $message
        );
    }

    /** @dataProvider incorrectDataProvider */
    public function deleteGameWithIncorrectId(ApiTester $apiTester, Example $provider): void
    {
        $apiTester->wantToTest('Delete game by incorrect id');

        $apiTester->sendDelete($provider['incorrectId']);

        $apiTester->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    private function incorrectDataProvider(): iterable
    {
        yield [
            'incorrectId' => 'asd'
        ];

        yield [
            'incorrectId' => '-1'
        ];
    }
}
