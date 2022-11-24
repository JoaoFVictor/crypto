<?php

namespace App\Repository\Cryptocurrency;

use App\Models\Cryptocurrency\CoinPrice;

class CoinPriceRepositoryEloquent implements CoinPriceRepositoryInterface
{
    public function __construct(private CoinPrice $model)
    {
    }

    public function store(array $data): CoinPrice
    {
        return $this->model->create($data);
    }

    public function findByDateTimeAndCoinName(string $dateTime, string $coinName): ?CoinPrice
    {
        return $this->model->whereDate('price_at', $dateTime)
            ->where('name', $coinName)
            ->first();
    }
}
