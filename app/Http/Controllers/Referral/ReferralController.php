<?php

namespace App\Http\Controllers\Referral;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Referral;
use App\Models\User;

class ReferralController extends Controller
{
    public static $bouns = "200";

    /**
   * generates a unique referral code
   * for every user
   *
    */
   public static function getReferralCode(){
       $code = time() -  mt_rand(100000000, 999999999);
       $random = strtoupper(Str::random(2));
       $new_code = $random.'-'.$code;
       $query = User::where('referral_code', $new_code)->first();
       while($query = true){
           
           return $new_code;
   
       }  
      
   }

   /**
   * saves referrals
   * for a user
   * @param $request
   *
    */

   public static function save($code, $referral_code=null){
       $create = Referral::create([
           'user_id' => UserController::getUserId($referral_code),
           'referred_code' => $code,
           'status' => 'not-fulfilled',
           'bonus_amount' => $this::$bonus,
       ]);
   }

     /**
   * get users you referred
   * for a user
   * @param $user_id
   *
    */
   public static function getMyReferred($user_id){
       return Referral::where('user_id', $user_id)->get();
   
   }

      /**
   * get user by referreal code
   * for a user
   * @param $referral_code
   *
    */
   public static function getByReferralCode($code){
       return Referral::where('referred_code', $code)->first();
   
   }

   /**
   * fulfill referreal 
   * for a user
   * @param $referral_code
   *
    */
   public static function fulfill($referral_code){
       $user = User::where('referral_code', $referral_code)->first();
       $referral = Referral::where(['referred_code' => $referral_code, 'status' => 'not-fulfilled'])->first();
       if(!empty($referral->id)){
       $referral->status = 'fulfilled';
       $referral->save();

       $data = [
          'user_id' => $referral->user_id,
          'amount' => $referral->bonus_amount,
          'purpose' => 'Referral bonus payment',
       ];
       WalletController::credit($data);
               }
   }
}
