<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Channel\ChannelAdminController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Image\ImageController;
use App\Http\Controllers\Wallet\WalletController;
use Carbon\Carbon;
use App\Models\AdsRate;
use DB, Validator, Auth;

class AdvertController extends Controller
{
      
     /**
    * saves advert details
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $ads_rate = AdsRate::where('id', $request['ads_rate_id'])->first();
        $no_days = self::noDays($request['from_date'], $request['ending_date']);
        $create = new Advert;
        $create->user_id = $request['user_id'];
        $create->title = $request['title'];
        $create->description = $request['description'];
        $create->ads_rate_id = $request['ads_rate_id'];
        $create->total_amount = $no_days * $ads_rate->daily_amount;
        $create->from_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request['from_date'])));
        $create->ending_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($request['ending_date'])));
        $create->image = ImageController::uploadPostImg($request['image']);
        $create->no_days = $no_days;
        $create->status = 'pending';
        $create->save();
        return ['id' => $create->id, 'total_amt'=>$create->total_amount];

    }

       
     /**
    * creates advert details
    *
    * @param $request
    *
    * @return id
     */
    public static function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'user_id' => 'required',
         'title' => 'required|max:70',
         'description' => 'required|max:200',
         'ads_rate_id' => 'required',
         'total_amount' => 'required',
         'no_days' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
     if(WalletController::checkAmt($request['user_id'], $request['total_amount'])){
         $save = self::save($request);
         $debit = [
             'user_id' => $request['user_id'],
             'amount' => $save['total_amt'],
             'purpose' => 'Ads funding',
         ];
        WalletController::debit($debit);
         $data = [
            'user_id' => $request['user_id'],
            'comment' => 'Your ad has been submitted successfully. It may take some time for approval before it starts showing',
         ];
         NotificationController::save($data);

         return response()->json([ 
            'status' =>'success',
            'message' => 'Ad created successfully. Thank you for advertising with us.'

         ]);
     }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Ad not created successfully because of insufficient balance in your wallet. Please fund your wallet and try again',
        ]);
     }

    }

    /**
     * claculates the number of
     * 
     * days form the two days entered
     * 
     * @return a date
     * 
     */
    public static function noDays($from_date, $ending_date){
        $fromdate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($from_date)));
        $endingdate = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($ending_date)));
        return $fromdate->diffInDays($endingdate);
    }

    /**
     * approves the advert
     * @param $advert_id
     * 
     * 
     * @return a rensponse
     * 
     */
    public static function approve($advert_id){
     $ad = Advert::where('id', $advert_id)->first();
     $ad->status = 'active';
     $result = $ad->save();
     if($result){
     $data = [
        'user_id' => $ad->user_id,
        'comment' => 'Your ad has been approved. Thank you for advertisin with us',
     ];
     NotificationController::save($data);

     return response()->json([ 
        'status' =>'success',
        'message' => 'Ad approved successfully.'

     ]);
     }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong ad was not approved successfully. Please try again'
    
         ]);
     }
    }

    /**
     * declines the advert
     * @param $advert_id
     * 
     * 
     * @return a rensponse
     * 
     */
    public static function decline($advert_id){
        $ad = Advert::where('id', $advert_id)->first();
        $ad->status = 'declined';
       $result = $ad->save();
       if($result){
        $debit = [
            'user_id' => $ad->user_id,
            'amount' => $ad->total_amount,
            'purpose' => 'Ads refunding',
        ];
       WalletController::credit($debit);
        $data = [
            'user_id' => $advert_id,
            'comment' => 'We are  sorry to inform you that your ad was declined. Please visit our ads policy page to make sure your ad complies with ou standard. Thanks',
         ];
         NotificationController::save($data);   
       
       return response()->json([ 
        'status' =>'success',
        'message' => 'Ad declined successfully',

     ]);
       }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong ad was not declined successfully. Please try again',
    
         ]);
       }

    }

    /**
     * returns all pending ads
     * 
     * 
     * @return collection
     * 
     */
    public static function getPendingAds(){
        $pendingads = Advert::where('status', 'pending')->orderBy('created_at', 'Asc')->paginate(30);
    }


    /**
     * this method returns the expired ads
     * 
     * the method is used by cron job
     * 
     * notifies them with email and app notification
     * 
     */
    public static function EndExpiredAds(){
      $ads =  Advert::where('status', 'active')->get();
      foreach($ads as $ad){
          if($ad->ending_date->isToday() || $ad->ending_date->isPast()){
              $ad->status = 'ended';
              $ad->save();
              $data = [
                  'user_id' => $ad->user_id,
                  'comment' => 'You ad has ended. To continue advertising you can post another one',
              ];
              NotificationController::save($data); 
          }
      }
    }

    /**
     * get my ads
     * 
     * @return collection
     */
    public static function getMyAds($user_id){
        $ads = Advert::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
    }

     /**
     * get declined ads
     * 
     * @return collection
     */
    public static function getDeclinedAds(){
        $ads = Advert::where('status', 'declined')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * gets all ads
     * 
     * @return collection
     * 
     */
    public static function get($id = null){
        if(!empty($id)){
    		return $data = Advert::where('id', $id)->with(['post'])->first();
    	}elseif(empty($id)){
    		  return $data = Advert::whereNotNull('id')->with(['post'])->paginate(20);
	    	}else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }


    /**
     * updates an ad
     * 
     * @param $request
     * 
     * @return response
     */
    public static function update($request){
        $update = Advert::where('id', $request['id'])->first();
        if(!empty($request['title'])){
            $validation = Validator::make($request->all(),
            [
           
             'title' => 'required|max:70',
           
            ]);
            
        if($validation->fails()){
            return $validation->errors();
        }
            $update->title = $request['title'];
        }

        if(!empty($request['description'])){
            $validation = Validator::make($request->all(),
            [
           
             'deescription' => 'required|max:200',
           
            ]);
            
        if($validation->fails()){
            return $validation->errors();
        }
            $update->description = $request['description'];
        }

        $update->save();
    }
    
    
}
