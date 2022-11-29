<?php

namespace Tests\Feature\Cryptocurrency\Commands;

use Carbon\Carbon;
use Codenixsv\CoinGeckoApi\Api\Simple;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CoinSetCurrentPriceCommandTest extends TestCase
{
    /**
     * A test to validate throw exception if dont receive prices.
     *
     * @return void
     */
    public function test_should_be_exception_if_dont_receive_prices()
    {
        $this->expectException(Exception::class);
        $this->expectDeprecationMessage('No price found for currency coins');

        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->willReturn([]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);
        $this->instance(CoinGeckoClient::class, $coinGeckoClientStub);

        Artisan::call('cryptocurrency:set-coin-current-price');
    }

    /**
     * A test to validate insertion in the database.
     *
     * @return void
     */
    public function test_successful_insert_in_database()
    {
        $dateTime = now();
        Carbon::setTestNow($dateTime);
        $coinPrice = 16577;
        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->willReturn(['bitcoin' => ['usd' => $coinPrice]]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);
        $this->instance(CoinGeckoClient::class, $coinGeckoClientStub);

        Artisan::call('cryptocurrency:set-coin-current-price');
        $this->assertDatabaseHas('coin_prices', [
            'name' => 'bitcoin',
            'price' => $coinPrice,
            'price_at' => $dateTime,
        ]);
    }
}
