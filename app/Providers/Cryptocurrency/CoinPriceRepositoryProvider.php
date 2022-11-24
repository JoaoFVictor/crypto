<?php

namespace App\Providers\Cryptocurrency;

use App\Repository\Cryptocurrency\CoinPriceRepositoryEloquent;
use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class CoinPriceRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CoinPriceRepositoryInterface::class, CoinPriceRepositoryEloquent::class);
    }
}
