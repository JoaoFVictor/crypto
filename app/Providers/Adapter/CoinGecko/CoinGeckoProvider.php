<?php

namespace App\Providers\Adapter\CoinGecko;

use App\Adapter\CoinGecko\CoinGeckoApi;
use App\Adapter\CoinGecko\CoinGeckoInterface;
use Illuminate\Support\ServiceProvider;

class CoinGeckoProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CoinGeckoInterface::class, CoinGeckoApi::class);
    }
}
