<?php

namespace App\Adapter\CoinGecko;

use Carbon\Carbon;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class CoinGeckoApi implements CoinGeckoInterface
{
    public function __construct(private readonly CoinGeckoClient $coinGeckoClient)
    {
    }

    public function getCoinCurrentPrice(array $coinsName): array
    {
        $coinsNameString = implode(',', $coinsName);
        return $this->coinGeckoClient->simple()->getPrice($coinsNameString, 'usd');
    }

    public function getCoinCurrentPriceHistory(string $coinName, Carbon $date): array
    {
        $dateFormated = $date->format('d-m-Y');
        return $this->coinGeckoClient->coins()->getHistory($coinName, $dateFormated);
    }
}
