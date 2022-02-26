<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsPartner\SyncManager;

class PartnerNewsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync news from partner source';

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
        return (new SyncManager)->handle(true);
    }
}
