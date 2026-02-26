<?php

namespace App\Console\Commands;

use App\Services\Web\ParseXML\ParseSettlements;
use Illuminate\Console\Command;

final class SettlementsSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:settlements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Settlements';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Start parsing');
        $settlements = new ParseSettlements();
        $settlements->parse();
        $this->info('Success');
    }
}
