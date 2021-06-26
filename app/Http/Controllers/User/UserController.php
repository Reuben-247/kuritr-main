<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserActivityLogController;
use App\Http\Controllers\Channel\UserChannelController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Referral\ReferralController;
use App\Http\Controllers\Email\Mailer;
use App\Http\Controllers\Email\EmailVerificationController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Notification\NotificationController;
use Illuminate\Http\Request;
use DB, Auth, Validator;
use App\Models\User;
use App\Models\Post;
use App\Models\Notification;
use Carbon\Carbon;


class UserController extends Controller
{ 
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    { 
         $save = new User;
        $save->name = $request['name'];
        $save->email = $request['email'];
        $save->password = bcrypt($request['password']);
        $save->mobile_no = $request['mobile_no'];
        $save->gender = $request['gender'];
        $save->user_name = $request['user_name'];
        $save->referral_code = ReferralController::getReferralCode();
        $save->save();
        return $save->id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {     $validation = Validator::make($request->all(),
        [
        'name' => 'required',
        "password" => "required|min:6|confirmed",
        "email" =>"required|unique:users|email",
        "user_name" =>"required",
        "gender" => "required|string",
        "mobile_no" => "nullable|numeric|unique:users",
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   
    try{
        DB::transaction(function() use ($request){
        $code = mt_rand(1000, 9999);
        $result = self::save($request);
         // setting init, email verify, init wallet
        SettingController::save($result);
       // WalletController::save($result);
        EmailVerificationController::save($result, $code);
        //Mailer::verifyEmail($request['email'], $code);
       Mailer::welcomeMail($request['email'], $request['name'], $code);
        // referrals
        $referral_code = ReferralController::getReferralCode($result);
        if($referral_code != null){
        if(!empty($request['referral_code']) && self::checkReferralCode($request['referral_code'])){
         ReferralController::save($referral_code, $request['referral_code']);
                    }
             }
        });
        $login = UserAuthController::loginAtReg($request);
            return response()->json([ 
                'status' =>'success',
                'message' => 'User created successfully',
                'token' => $login['token'],
                'user' => $login['user'],
                ]);

        }catch(Exception $e){
            return response()->json([ 
                'status' =>'error',
                'message' => 'User not created successfully'
             ]);

        }
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong, user not created successfully. Please try again'
         ]);

        //
    }

       /**
     * This method unblocks
     * 
     *  a user
     * 
     * @param $id
     */
    public static function unblock($id){
        if(!empty($id)){
    		 $data = User::where('id', $id)->first();
             $data->status = 'active';
             $data->save();
             $unblock = ['user_id' => $data->id,
             'comment' => 'Your account has been re-activated',
                 ];
             NotificationController::save($unblock);
             return response()->json([
                'status' => 'success',
                'message' => 'Your account has been re-activated'
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

   /**
     * This method deline
     * 
     *  a user
     * 
     * @param $id
     */
    public static function ban($id){
        if(!empty($id)){
    		 $data = User::where('id', $id)->first();
             $data->status = 'banned';
             $data->save();
             $decline = ['user_id' => $data->id,
             'comment' => 'Your account has been banned from this paltform'
                 ];
                 NotificationController::save($decline);
             return response()->json([
                'status' => 'success',
                'message' => 'Account banned'
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, account could not be banned'
            ]);
        }
    }

    /**
     * This method retrieves
     * 
     *  category
     * 
     * @param $id
     */
    public static function suspend($id){
        if(!empty($id)){
    		 $data = User::where('id', $id)->first();
             $data->status = 'suspend';
             $data->save();
             $suspend = ['user_id' => $data->id,
             'comment' => 'Your account has been suspended',
                 ];
            NotificationController::save($suspend);
             return response()->json([
                'status' => 'success',
                'message' => 'User has been suspended'
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }


    

     /**
     * This method retrieves
     * 
     *  user(s)
     * 
     * @param $id
     */
    public static function get($id = null){
        if(!empty($id)){
            return $data = User::where(['id' => $id, 'status' => 'active'])->with(['wallet', 'notification',
            'transactionHistory'])->first();
    	}elseif(empty($id)){
              return $data = User::whereNotNull('id')->where('status', 'active')->with(['wallet', 'notification',
               'transactionHistory'])->orderBy('created_at', 'DESC')->paginate(25);
	    	}else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

      /**
     * This method retrieves
     * 
     * blocked user(s)
     * 
     */

     public function blockedUsers(){
         return User::where('status', 'blocked')->with(['postimage'])->orderBy('created_at', 'DESC')->paginate(25);
     }

    /**
     * This method deletes
     * 
     *  user
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = User::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'User deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong User not deleted successfully'
            ]);

        }
    }

    /**
     * This method updates
     * 
     *  a user
     * 
     * @param $id
     */
    public static function update(Request $request){
    $update = User::find($request['id']);

    if(!empty($request['name'])){
    $update->name = $request['name'];
    }

    if(!empty($request['gender'])){
        $update->gender = $request['gender'];
        }

    if(!empty($request['mobile_no'])){
            $update->mobile_no = $request['mobile_no'];
                }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'User updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong User not updated successfully'
        ]);

    }


    }



  /**
     * This method verifies a channel
     * 
     * @param $id
     * 
     * @return response
     */
    public static function verifyUser($id){
        if(!empty($id)){
            $user = User::where('id', $id)->first();
            $user->profile_verified = 'profile_verified';
            $ok = $user->save();
            $data = [
                'user_id' => $user->id,
                'comment' => 'Congrats. Your account has been verified.',
                ];
                NotificationController::save($data);
            if($ok){
                return response()->json([
                    'status' => 'success',
                    'message' => 'User account verified successfully',
                ]);
            }else{
             return response()->json([
                 'status' => 'error',
                 'message' => 'Something went wrong user account not verified successfully. Please try again',
             ]); 
            }
        }
      }

/**
* Uploading image
*
* @param $request
*/

public function uploadImg(Request $request){
 if(!empty($request->file('avatar'))){
        $user = User::find($request['user_id']);
         $user_type = 'user';
          $user->avatar = ImageController::uploadAvatar($request->file('avatar'));
          $user->save();
          return response()->json([
            'status' => 'success',
            'message' => 'Profile image updated successfully'
            ]);
        }
}

/**
* changes admin email
*
*@param $request
*/
public static function changeEmail(Request $request){
    $data = $request->all();
    $validation = Validator::make($request->all(),
    [
    "password" => "required",
    "new_email" => "required|email"
    ]);
    
   if($validation->fails()){
    return $validation->errors();
   }
   try{
       DB::transaction( function() use ($data){ 
    $code = mt_rand(1000, 9999);
    $confirm = User::where('id', $data['user_id'])->first();
    if(password_verify($data['password'], $confirm->password)){
      $update = User::find($data['user_id']);
      $update->email = bcrypt($data['new_email']);
      $update->email_verified = 'no';
      $update->save();
      EmailVerificationController::save($update->id, $code);
      // send verification mail
    //  $code = mt_rand(1000, 9999);
       return response()->json([
            'status' => 'success',
            'message' => 'Email updated successfully. Please note that you still have to verify this email']);
       } else{
           return response()->json([
            'status' => 'error',
            'message' => 'Your password is not correct.',
           ]);
       } 
      });
    }catch(Exception $e){
      return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, email not updated successfully. Please try again'
            ]);
        }
  }




  /**
 * gets the total number of
 * user signed up today
 * 
 * @return response
 * 
 */
public static function totalUserToday(){
    $now = Carbon::today();
    return User::whereDate('created_at', $now)->count();
}

/**
 * gets the total number of
 * user signed up this week
 * 
 * @return response
 * 
 */
public static function totalUserThisWeek(){
    $now = Carbon::now();
    $weekstart = $now->startOfWeek();
    return User::whereDate('created_at', '>=', $weekstart)->count();
}


/**
 * gets the total number of
 * user signed up this month
 * 
 * @return response
 * 
 */
public static function totalUserThisMonth(){
    $now = Carbon::now();
    $monthstart = $now->startOfMonth();
    return User::whereDate('created_at', '>=', $monthstart)->count();
}

/**
 * gets the total number of
 * user signed up this year
 * 
 * @return response
 * 
 */
public static function totalUserThisYear(){
    $now = Carbon::now();
    $yearstart = $now->startOfYear();
    return User::whereDate('created_at', '>=', $yearstart)->count();
}

/**
 * gets the total number of
 * all users signed on the platform
 * 
 * @return response
 * 
 */
public static function totalUser(){
    return User::whereNotNull('id')->count();
}



}
