<?php

namespace App\Action\Cryptocurrency;

use App\Adapter\CoinGecko\CoinGeckoInterface;
use App\Enums\Cryptocurrency\EnumCoin;
use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Cache;

class CoinSetPriceAction
{
    public function __construct(private readonly CoinGeckoInterface $coinGeckoClient, private readonly CoinPriceRepositoryInterface $coinPriceRepository)
    {
    }

    public function handle(): void
    {
        $coinsName = array_column(EnumCoin::cases(), 'value');
        $coinsPrice = $this->coinGeckoClient->getCoinCurrentPrice($coinsName);
        if (empty($coinsPrice)) {
            throw new Exception("No price found for currency coins");
        }

        foreach ($coinsPrice as $key => $value) {
            Cache::put("coin-{$key}-current-price", ['coin' => $key, 'price' => current($value)], config('cache.time.five_minutes'));
            $data = ['name' => $key, 'price' => current($value), 'price_at' => now()];
            $this->coinPriceRepository->store($data);
        }
    }
}
