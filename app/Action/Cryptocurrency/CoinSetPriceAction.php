<?php

namespace App\Action\Cryptocurrency;

use App\Enums\Cryptocurrency\EnumCoin;
use App\Repository\Cryptocurrency\CoinPriceRepositoryInterface;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Exception;

class CoinSetPriceAction
{
    public function __construct(private readonly CoinGeckoClient $coinGeckoClient, private readonly CoinPriceRepositoryInterface $coinPriceRepository)
    {
    }

    public function handle(): void
    {
        $coinsName = implode(',', array_column(EnumCoin::cases(), 'value'));
        $coinsPrice = $this->coinGeckoClient->simple()->getPrice($coinsName, 'usd');
        if (empty($coinsPrice)) {
            throw new Exception("No price found for currency coins");
        }

        foreach ($coinsPrice as $key => $value) {
            $data = ['name' => $key, 'price' => current($value), 'price_at' => now()];
            $this->coinPriceRepository->store($data);
        }
    }
}
