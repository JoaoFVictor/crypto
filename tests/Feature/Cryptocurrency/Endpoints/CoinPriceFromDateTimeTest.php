<?php

namespace Tests\Feature\Cryptocurrency\Endpoints;

use App\Enums\Cryptocurrency\EnumCoin;
use App\Models\Cryptocurrency\CoinPrice;
use Carbon\Carbon;
use Codenixsv\CoinGeckoApi\Api\Coins;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CoinPriceFromDateTimeTest extends TestCase
{
    private const ROUTE = 'cryptocurrency.price.history';

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
                'date',
            ])->assertJson([
                'errors' => [
                    'coin' => ['The coin field is required.'],
                    'date' => ['The date field is required.'],
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
        $response = $this->getJson(route(self::ROUTE, ['coin' => 1, 'date' => 'a']));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                'coin',
                'date',
            ])->assertJson([
                'errors' => [
                    'coin' => ['The selected coin is invalid.'],
                    'date' => ['The date does not match the format Y-m-d H:i.'],
                ],
            ], true);
    }

    /**
     * A test to validate data entry into the database and valid response.
     *
     * @return void
     */
    public function test_successful_response_with_insert_in_db()
    {
        $coinPrice = 1425;
        $dateTime = '2022-11-24 00:50';
        $coinsStub = $this->createMock(Coins::class);
        $coinsStub->method('getHistory')
            ->with(EnumCoin::Bitcoin->value, '24-11-2022')
            ->willReturn([
                'market_data' => [
                    'current_price' => [
                        'usd' => $coinPrice,
                    ],
                ],
            ]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('coins')
            ->willReturn($coinsStub);
        $this->instance(CoinGeckoClient::class, $coinGeckoClientStub);

        $response = $this->getJson(route(self::ROUTE, ['coin' => EnumCoin::Bitcoin, 'date' => $dateTime]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'coin' => EnumCoin::Bitcoin->value,
                    'price' => $coinPrice,
                ],
            ], true);
        $this->assertDatabaseHas('coin_prices', [
            'name' => 'bitcoin',
            'price' => $coinPrice,
            'price_at' => Carbon::createFromTimeString($dateTime),
        ]);
    }

    /**
     * A test to validate history already existing in the db and valid response.
     *
     * @return void
     */
    public function test_successful_response_with_get_data_from_db()
    {
        $coinPrice = 1425;
        $dateTime = '2022-11-24 00:50';
        CoinPrice::factory()->create([
            'name' => 'bitcoin',
            'price' => $coinPrice,
            'price_at' => Carbon::createFromTimeString($dateTime),
        ]);

        $response = $this->getJson(route(self::ROUTE, ['coin' => EnumCoin::Bitcoin, 'date' => $dateTime]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'coin' => EnumCoin::Bitcoin->value,
                    'price' => $coinPrice,
                ],
            ], true);
    }
}
