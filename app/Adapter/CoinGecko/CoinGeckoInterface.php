<?php

namespace App\Adapter\CoinGecko;

use Carbon\Carbon;

interface CoinGeckoInterface
{
    public function getCoinCurrentPrice(array $coinsName): array;

    public function getCoinCurrentPriceHistory(string $coinName, Carbon $date): array;
}
