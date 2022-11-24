<?php

namespace App\Console\Commands\Cryptocurrency;

use App\Action\Cryptocurrency\CoinSetPriceAction;
use Illuminate\Console\Command;

class CoinSetCurrentPriceCommand extends Command
{
    public function __construct(private readonly CoinSetPriceAction $coinSetPriceAction)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cryptocurrency:set-coin-current-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set coin current price';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->coinSetPriceAction->handle();
        return Command::SUCCESS;
    }
}
