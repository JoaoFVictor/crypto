<?php

namespace Tests\Unit\Action\Cryptocurrency;

use App\Action\Cryptocurrency\CoinCurrentPriceAction;
use Closure;
use Codenixsv\CoinGeckoApi\Api\Simple;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CoinCurrentPriceActionTest extends TestCase
{
    /**
     * A test to validate if an exception is generated if a price is not found.
     *
     * @return void
     */
    public function test_should_be_exception_if_dont_receive_price()
    {
        $coinName = 'coinTest';
        $this->expectException(Exception::class);
        $this->expectDeprecationMessage("No price found for currency {$coinName}");

        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->with($coinName, 'usd')
            ->willReturn([]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);

        $action = new CoinCurrentPriceAction($coinGeckoClientStub);
        $action->handle($coinName);
    }

    /**
     * A test to validate cache key and function return.
     *
     * @return void
     */
    public function test_expect_array_with_coin_name_and_price()
    {
        $coinName = 'coinTest';
        $coinPrice = 1192.37;
        Cache::shouldReceive('remember')
            ->once()
            ->with("coin-{$coinName}-current-price", 300, Closure::class)
            ->andReturn(['coin' => $coinName, 'price' => $coinPrice]);

        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->with($coinName, 'usd')
            ->willReturn([$coinPrice]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);

        $action = new CoinCurrentPriceAction($coinGeckoClientStub);
        $response = $action->handle($coinName);

        $this->assertEquals(['coin' => $coinName, 'price' => $coinPrice], $response);
    }
}
