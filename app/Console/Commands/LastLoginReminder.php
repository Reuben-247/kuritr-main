<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Email\Mailer;
use App\Models\User;
use Carbon\Carbon;

class LastLoginReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'last:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the an email to the user who stays upto 30 days withoust login to the platform';

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
    {   $now = Carbon::now();
        $lastlogin = $now->subDays(21);
        $users = User::where('status', 'active')->whereDate('last_login_date', '<=', $lastlogin)->get();
        foreach($users as $user){
          $loginlast =  Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($user->last_login_date)));
            if($loginlast->day == $now->day){
        Mailer::lastLoginReminder($user->email, $user->name);
            }
        }
    }
}
