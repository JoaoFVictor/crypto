<?php

namespace App\Action\Cryptocurrency;

use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Illuminate\Support\Facades\Cache;

class CoinCurrentPriceAction
{
    private readonly CoinGeckoClient $coinGeckoClient;

    public function __construct()
    {
        $this->coinGeckoClient = new CoinGeckoClient();
    }

    public function handle(string $coinName): array
    {
        return Cache::remember("coin-{$coinName}-current-price", config('cache.time.five_minutes'), function () use ($coinName) {
            $data = collect($this->coinGeckoClient->simple()->getPrice($coinName, 'usd'))->flatten()->toArray();

            return [
                'coin' => $coinName,
                'price' => current($data),
            ];
        });
    }
}
