<?php

namespace Tests\Unit\Action\Cryptocurrency;

use App\Action\Cryptocurrency\CoinPriceFromDateTimeAction;
use App\Adapter\CoinGecko\CoinGeckoApi;
use App\Models\Cryptocurrency\CoinPrice;
use App\Repository\Cryptocurrency\CoinPriceRepositoryEloquent;
use Closure;
use Exception;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CoinPriceFromDateTimeActionTest extends TestCase
{
    /**
     * A test to validate if an exception is generated if a history is not found.
     *
     * @return void
     */
    public function test_should_be_exception_if_dont_receive_history()
    {
        $coinName = 'coinTest';
        $dateTime = '2022-11-24 00:50';
        $this->expectException(Exception::class);

        $coinGeckoClientStub = $this->createMock(CoinGeckoApi::class);
        $coinGeckoClientStub->method('getCoinCurrentPriceHistory')
            ->willThrowException(new Exception());
        $coinPriceRepositoryEloquentStub = $this->createMock(CoinPriceRepositoryEloquent::class);
        $coinPriceRepositoryEloquentStub->method('findByDateTimeAndCoinName')
            ->willReturn(null);

        $action = new CoinPriceFromDateTimeAction($coinGeckoClientStub, $coinPriceRepositoryEloquentStub);
        $action->handle($coinName, $dateTime);
    }

    /**
     * A test to validate cache key, function return and insert in db.
     *
     * @return void
     */
    public function test_expect_array_with_coin_name_and_price_and_insert_in_db()
    {
        $coinName = 'coinTest';
        $dateTime = '2022-11-24 00:50';
        $coinPrice = 1192.37;
        Cache::shouldReceive('remember')
            ->once()
            ->with("coin-{$coinName}-date-{$dateTime}-price", 86400, Closure::class)
            ->andReturn(['coin' => $coinName, 'price' => $coinPrice]);

        $coinGeckoClientStub = $this->createMock(CoinGeckoApi::class);
        $coinGeckoClientStub->method('getCoinCurrentPriceHistory')
            ->willReturn([
                'market_data' => [
                    'current_price' => [
                        'usd' => $coinPrice,
                    ],
                ],
            ]);
        $coinPriceRepositoryEloquentStub = $this->createMock(CoinPriceRepositoryEloquent::class);
        $coinPriceRepositoryEloquentStub->method('findByDateTimeAndCoinName')
            ->willReturn(null);
        $coinPriceRepositoryEloquentStub->method('store')
            ->willReturn(CoinPrice::factory()->make(['name' => $coinName, 'price' => $coinPrice, 'price_at' => $dateTime]));

        $action = new CoinPriceFromDateTimeAction($coinGeckoClientStub, $coinPriceRepositoryEloquentStub);
        $response = $action->handle($coinName, $dateTime);

        $this->assertEquals(['coin' => $coinName, 'price' => $coinPrice], $response);
    }

    /**
     * A test to validate cache key and function return.
     *
     * @return void
     */
    public function test_expect_array_with_coin_name_and_price()
    {
        $coinName = 'coinTest';
        $dateTime = '2022-11-24 00:50';
        $coinPrice = 1192.37;
        Cache::shouldReceive('remember')
            ->once()
            ->with("coin-{$coinName}-date-{$dateTime}-price", 86400, Closure::class)
            ->andReturn(['coin' => $coinName, 'price' => $coinPrice]);

        $coinGeckoClientStub = $this->createMock(CoinGeckoApi::class);
        $coinPriceRepositoryEloquentStub = $this->createMock(CoinPriceRepositoryEloquent::class);
        $coinPriceRepositoryEloquentStub->method('findByDateTimeAndCoinName')
            ->willReturn(CoinPrice::factory()->make());

        $action = new CoinPriceFromDateTimeAction($coinGeckoClientStub, $coinPriceRepositoryEloquentStub);
        $response = $action->handle($coinName, $dateTime);

        $this->assertEquals(['coin' => $coinName, 'price' => $coinPrice], $response);
    }
}
