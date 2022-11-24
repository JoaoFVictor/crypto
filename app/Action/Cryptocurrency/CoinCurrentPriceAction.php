<?php

namespace App\Action\Cryptocurrency;

use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;
use Illuminate\Support\Facades\Cache;

class CoinCurrentPriceAction
{
    public function __construct(private readonly CoinGeckoClient $coinGeckoClient)
    {
    }

    public function handle(string $coinName): array
    {
        return Cache::remember("coin-{$coinName}-current-price", config('cache.time.five_minutes'), function () use ($coinName) {
            $data = collect($this->coinGeckoClient->simple()->getPrice($coinName, 'usd'))->flatten()->toArray();
            dd($data);
            if (empty($data)) {
                throw new Exception("No price found for currency {$coinName}");
            }

            return [
                'coin' => $coinName,
                'price' => current($data),
            ];
        });
    }
}
