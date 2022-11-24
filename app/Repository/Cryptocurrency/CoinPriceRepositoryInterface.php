<?php

namespace App\Repository\Cryptocurrency;

use App\Models\Cryptocurrency\CoinPrice;

interface CoinPriceRepositoryInterface
{
    public function store(array $data): CoinPrice;

    public function findByDateTimeAndCoinName(string $dateTime, string $coinName): ?CoinPrice;
}
