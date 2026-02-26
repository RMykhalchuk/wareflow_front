<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Session;

final class ClearSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        // Clear the session data
        Session::flush();

        // Optionally, you can regenerate the session ID
        Session::regenerate();

        // Redirect or perform other actions as needed

        return Command::SUCCESS;
    }
}
