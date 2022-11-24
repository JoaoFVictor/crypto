<?php

namespace App\Action\Cryptocurrency;

use App\Enums\Cryptocurrency\EnumCoin;
use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinSetPriceAction
{
    private readonly CoinGeckoClient $coinGeckoClient;

    public function __construct(private readonly CoinPriceRepositoryInterface $coinPriceRepository)
    {
        $this->coinGeckoClient = new CoinGeckoClient();
    }

    public function handle(): void
    {
        $coinsName = implode(',', array_column(EnumCoin::cases(), 'value'));
        $coinPrice = $this->coinGeckoClient->simple()->getPrice($coinsName, 'usd');
        foreach ($coinPrice as $key => $value) {
            $data = ['name' => $key, 'price' => current($value), 'price_at' => now()];
            $this->coinPriceRepository->store($data);
        }
    }
}
