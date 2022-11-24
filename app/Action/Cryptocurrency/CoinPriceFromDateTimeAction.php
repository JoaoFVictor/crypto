<?php

namespace App\Action\Cryptocurrency;

use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Carbon\Carbon;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Support\Facades\Cache;

class CoinPriceFromDateTimeAction
{
    public function __construct(private readonly CoinGeckoClient $coinGeckoClient, private readonly CoinPriceRepositoryInterface $coinPriceRepository)
    {
    }

    public function handle(string $coinName, string $dateTime): array
    {
        return Cache::remember("coin-{$coinName}-date-{$dateTime}-price", config('cache.time.onde_day'), function () use ($coinName, $dateTime) {
            $coinPrice = $this->coinPriceRepository->findByDateTimeAndCoinName($dateTime, $coinName);
            if (is_null($coinPrice)) {
                $dateFormated = Carbon::createFromTimeString($dateTime)->format('d-m-Y');
                $coinValuePrice = $this->coinGeckoClient->coins()->getHistory($coinName, $dateFormated)['market_data']['current_price']['usd'];
                $coinPrice = $this->coinPriceRepository->store(['name' => $coinName, 'price' => $coinValuePrice, 'price_at' => $dateTime]);
            }

            return [
                'coin' => $coinPrice->name,
                'price' => $coinPrice->price,
            ];
        });
    }
}
