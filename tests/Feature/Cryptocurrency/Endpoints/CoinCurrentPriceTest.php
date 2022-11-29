<?php

namespace Tests\Feature\Cryptocurrency\Endpoints;

use App\Enums\Cryptocurrency\EnumCoin;
use Codenixsv\CoinGeckoApi\Api\Simple;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CoinCurrentPriceTest extends TestCase
{
    private const ROUTE = 'cryptocurrency.price.current';

    /**
     * A test to validate request required rules.
     *
     * @return void
     */
    public function test_should_be_return_validation_error_if_coin_filled_not_informed()
    {
        $response = $this->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'coin',
            ])->assertJson([
                'errors' => [
                    'coin' => ['The coin field is required.'],
                ],
            ], true);
    }

    /**
     * A test to validate request valid rules.
     *
     * @return void
     */
    public function test_should_be_return_validation_error_if_coin_filled_not_valid()
    {
        $response = $this->getJson(route(self::ROUTE, ['coin' => 1]));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'coin',
            ])->assertJson([
                'errors' => [
                    'coin' => ['The selected coin is invalid.'],
                ],
            ], true);
    }

    /**
     * A test to validate valid response.
     *
     * @return void
     */
    public function test_successful_response()
    {
        $coinPrice = 16577;
        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->with(EnumCoin::Bitcoin->value, 'usd')
            ->willReturn(['bitcoin' => ['usd' => $coinPrice]]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);
        $this->instance(CoinGeckoClient::class, $coinGeckoClientStub);

        $response = $this->getJson(route(self::ROUTE, ['coin' => EnumCoin::Bitcoin]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'coin' => EnumCoin::Bitcoin->value,
                    'price' => $coinPrice,
                ],
            ], true);
    }
}
