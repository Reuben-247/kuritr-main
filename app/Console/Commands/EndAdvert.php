<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Advert\AdvertController;

class EndAdvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:ads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ends ad that have expired';

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
        AdvertController::EndExpiredAds();
    }
}
