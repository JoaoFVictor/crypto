<?php

namespace Tests\Unit\Action\Cryptocurrency;

use App\Action\Cryptocurrency\CoinSetPriceAction;
use App\Enums\Cryptocurrency\EnumCoin;
use App\Repository\Cryptocurrency\CoinPriceRepositoryEloquent;
use Codenixsv\CoinGeckoApi\Api\Simple;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Tests\TestCase;

class CoinSetPriceActionTest extends TestCase
{
    private $coinsName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinsName = implode(',', array_column(EnumCoin::cases(), 'value'));
    }

    /**
     * A test to validate if an exception is generated if a price is not found.
     *
     * @return void
     */
    public function test_should_be_exception_if_dont_receive_price()
    {
        $this->expectException(Exception::class);
        $this->expectDeprecationMessage("No price found for currency coins");

        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->with($this->coinsName, 'usd')
            ->willReturn([]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);
        $coinPriceRepositoryEloquentStub = $this->createMock(CoinPriceRepositoryEloquent::class);

        $action = new CoinSetPriceAction($coinGeckoClientStub, $coinPriceRepositoryEloquentStub);
        $action->handle();
    }

    /**
     * A test to validate that prices are being entered into the db.
     *
     * @return void
     */
    public function test_expect_insert_prices_in_db()
    {
        $simpleStub = $this->createMock(Simple::class);
        $simpleStub->method('getPrice')
            ->with($this->coinsName, 'usd')
            ->willReturn(['bitcoin' => ['usd' => 16577], 'cosmos' => ['usd' => 10]]);
        $coinGeckoClientStub = $this->createMock(CoinGeckoClient::class);
        $coinGeckoClientStub->method('simple')
            ->willReturn($simpleStub);
        $coinPriceRepositoryEloquentStub = $this->createMock(CoinPriceRepositoryEloquent::class);
        $coinPriceRepositoryEloquentStub->expects(self::exactly(2))
            ->method('store');

        $action = new CoinSetPriceAction($coinGeckoClientStub, $coinPriceRepositoryEloquentStub);
        $action->handle();
    }
}
