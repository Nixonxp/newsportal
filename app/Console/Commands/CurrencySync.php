<?php

namespace App\Console\Commands;

use App\Services\Currency\SyncManager;
use Illuminate\Console\Command;

class CurrencySync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:sync {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize currency from external api';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = $this->option('force');

        return (new SyncManager())->handle(null, $force, true);
    }
}
