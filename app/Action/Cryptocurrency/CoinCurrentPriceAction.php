<?php

namespace App\Action\Cryptocurrency;

use App\Adapter\CoinGecko\CoinGeckoInterface;
use Exception;
use Illuminate\Support\Facades\Cache;

class CoinCurrentPriceAction
{
    public function __construct(private readonly CoinGeckoInterface $coinGeckoClient)
    {
    }

    public function handle(string $coinName): array
    {
        return Cache::remember("coin-{$coinName}-current-price", config('cache.time.five_minutes'), function () use ($coinName) {
            $data = $this->coinGeckoClient->getCoinCurrentPrice([$coinName]);

            if (empty($data)) {
                throw new Exception("No price found for currency {$coinName}");
            }

            return [
                'coin' => $coinName,
                'price' => $data[$coinName]['usd'],
            ];
        });
    }
}
