<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailVerification;
use Carbon\Carbon;

class ClearEmailVerificationTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears email verication table every 24 hrs';

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
        $verify_code = EmailVerification::where('status', 'active')->get();
        foreach($verify_code as $verify){
        if($verify->created_at->diffInDays(carbon::now()) >= 1){
            $verify->delete();
             }
        }
    }
}
