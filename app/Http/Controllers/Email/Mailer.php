<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use App\Mail\WelcomeMail;
use App\Mail\WelcomeMailAdmin;
use App\Mail\PasswordResetMail;
use App\Mail\VerifyEmail;
use App\Mail\CreditMail;
use App\Mail\DebitMail;
use App\Mail\GenericMail;
use App\Models\User;
use Mail;
use Carbon\Carbon;

class Mailer extends Controller
{
   /**
   * send password reset mail
   * generates a random password 
   * and sends to the registered mail
   *
   */ 
  public function postResetPassword(Request $request)
  {   $this->validate($request,
      [  'email'=>'required|email',
        
         ]);
      $sentpassword = mt_rand(100000, 1000000);
      $dbpassword = bcrypt($sentpassword);
      $data = [ 'password' => $sentpassword
                      ];
       $check = User::where('email', $request['email'])->first();
       if($check){
        $check->password = $dbpassword;
        $check->save();
        $data = ['password' => $sentpassword];
       // $check->password = $sentpassword;
        Mail::to($request['email'])->send(new PasswordResetMail($sentpassword));
        return response()->json(['status' => 'success',
                                 'message' => 'Account reset successfully. Please check your email'
                                   ]);
     
      }else{
       return response()->json([
           'status' => 'error',
           'message' => 'Email not registered in this platform. Please check if email is correct and try again'
             ]);
         // return redirect()->back()->with('status', 'Email not registered in this platform. Please check if email is correct and try again');
          
      }
  }

  /**
  * This method sends a mail to contact form
  *
  *
  */
  public function sendContact(Request $request){
      $this->validate($request,
      [ 'email'=>'required|email',
        'name'=>'required',
        'subject'=>'required',
        'content' => 'required',          
         ]);
     $delay = (new \Carbon\Carbon)->now()->addMinutes(2);
    Mail::to('support@digitmoni.com')->later($delay, new ContactMail($request['name'], $request['email'], $request['subject'], $request['content']));
    //return redirect()->back()->with('success', 'Contact mail sent successfully. Thanks for contacting us');
    return response()->json([
        'status' => 'success',
        'message' => 'Contact mail sent successfully. Thanks for contacting us'
          ]);
    }

  /**
  * This method sends a welcome mail
  * to a new user
  *
  */
  public static function welcomeMail($email, $name, $code){
      $delay = (new \Carbon\Carbon)->now()->addMinutes(1);
      Mail::to($email)->later($delay, new WelcomeMail($name, $code));
    }

  /**
  * This method sends a welcome mail
  * to a new admin
  *
  */
  public static function welcomeMailAdmin($email, $name, $password){
    $delay = (new \Carbon\Carbon)->now()->addMinutes(1);
    Mail::to($email)->later($delay, new WelcomeMailAdmin($name, $password));
   }

  /**
  * This method sends a verification 
  * code to a user
  *
  */
  public static function verifyEmail($email, $code){
    //$delay = (new \Carbon\Carbon)->now()->addMinutes(1);
    Mail::to($email)->send( new VerifyEmail($code));
   }

 /**
  * This method sends a notification email
  * to a user
  *
  */
  public static function creditMail($email, $amount, $balance, $purpose){
    $delay = (new \Carbon\Carbon)->now()->addMinutes(1);
    Mail::to($email)->later($delay, new CreditMail($amount, $balance, $purpose));
    }

 /**
  * This method sends a notification email
  * to a user
  * $amount, $balance, $wallet_no
  */
  public static function debitMail($email, $amount, $balance, $purpose){
    $delay = (new \Carbon\Carbon)->now()->addMinutes(1);
    Mail::to($email)->later($delay, new DebitMail($amount, $balance, $purpose));
   }

   /**
    * Sends email to users who has noe
    *
    * logged in for about 30 days
    *
    *
    */
    public static function lastLoginReminder($email, $name){
      Mail::to($email)->later($delay, new LastLoginMail($amount, $balance, $purpose));
    }


}
