<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Email\Mailer;
use Illuminate\Http\Request;
use App\Models\EmailVerification;
use App\Models\User;
use DB;

class EmailVerificationController extends Controller
{
    /**
     *  stores a email verifaication 
     *  details to the database
     *  @param $user_id, $code
     */
    public static function save($user_id, $code){
        $save = EmailVerification::create([
         'user_id' => $user_id,
         'verify_code' => $code,
        ]);
    }

     /**
     *  stores a email verifaication 
     *  details to the database
     *  @param $user_id, $code
     */
    public static function create($user_id){
        $code = mt_rand(1000, 9999);
        $user = User::find($user_id);
        $save = EmailVerification::create([
         'user_id' => $user_id,
         'verify_code' => $code,
        ]);
        // send verification email to the user
        Mailer::verifyEmail($user->email, $code);
        if($save){
            return response()->json([
                'status' => 'success',
                'message' => 'Email verification code sent successfully. Please login to the email used to sign up to get code.',
            ]);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Email verification code not sent successfully. Please try again.',
            ]);  
        }
    }

    /**
     *  verifies a user
     *  
     *  details to the database
     *  @param $user_id, $code
     */
    public static function verify($user_id, $code){
        DB::transaction(function() use ($user_id, $code){
            try{
     $verify = EmailVerify::where('user_id', $user_id)->where('verify_code', $code)
                            ->where('status', 'not-verified')->latest();
     $verify->status = 'verified';
     $verify->save();
     $user = User::find($user_id);
     $user->email_verified = 'yes';
     $user->save();
      return response()->json([
          'status' => 'success',
          'message' => 'User email verified successfully',
      ]);
    }catch(Exception $e){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong user email could not be verified successfully.
                     Please try sending another a new code to verify.',
                ]);

            }
    });
    }

     /**
     *  retuns a email verifaication 
     *  details to the database
     *  @param $id
     */
    public static function get($id = null){
        if(!empty($id)){
    		return $data = EmailVerification::where('id', $id)->first();
    	}elseif(empty($id)){
    		  return $data = EmailVerification::where('id', '!=', null)->paginate(20);
	    	}else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

     /**
     *  retuns a email verifaication 
     *  details to the database
     *  @param $id
     */
    public static function delete($id){
       $delete = EmailVerification::destroy($id);
       if($delete){
        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully',
            ]);
       }else{
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, verification data not deleted',
            ]);
       }
    }
}
