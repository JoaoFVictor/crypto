<?php

namespace App\Action\Cryptocurrency;

use App\Adapter\CoinGecko\CoinGeckoInterface;
use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CoinPriceFromDateTimeAction
{
    public function __construct(private readonly CoinGeckoInterface $coinGeckoClient, private readonly CoinPriceRepositoryInterface $coinPriceRepository)
    {
    }

    public function handle(string $coinName, string $dateTime): array
    {
        return Cache::remember("coin-{$coinName}-date-{$dateTime}-price", config('cache.time.onde_day'), function () use ($coinName, $dateTime) {
            $coinPrice = $this->coinPriceRepository->findByDateTimeAndCoinName($dateTime, $coinName);
            if (is_null($coinPrice)) {
                $carbonDate = Carbon::createFromTimeString($dateTime);
                $coinValuePrice = $this->coinGeckoClient->getCoinCurrentPriceHistory($coinName, $carbonDate)['market_data']['current_price']['usd'];
                $coinPrice = $this->coinPriceRepository->store(['name' => $coinName, 'price' => $coinValuePrice, 'price_at' => $dateTime]);
            }

            return [
                'coin' => $coinPrice->name,
                'price' => $coinPrice->price,
            ];
        });
    }
}
