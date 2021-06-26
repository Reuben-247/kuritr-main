<?php

namespace App\Http\Controllers\ParticipationStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParticipationStatus;
use DB, Validator, Auth;

class ParticipationStatusController extends Controller
{
    
       /**
    * saves participation status
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = ParticipationStatus::create([
            
            'user_id' => $request['user_id'],
            'level_id' => $request['level_id'],
            'status' => 'active', // fullfilled or claimed
           
        ]);
     }
      /**
     * This method creates 
     * 
     * exam type
     * 
     * @param $request
     * 
     * @return response
     */
    public function create($request){
             
  
        // return $request->all();
             $result = self::save($request);
           
             if($result){
                 return response()->json([ 
                     'status' =>'success',
                     'message' => 'Participation status created successfully',
     
                                          ]);
             }else{
                 return response()->json([ 
                     'status' =>'error',
                     'message' => 'Participation status reward not created successfully. please try again',
                 ]);
     
             }
        
     
         }

    /**
     * This method promotes a user
     * 
     *  by increasing participation status 
     * 
     * @param $user_id
     */
    public static function promoteUser($user_id){
        $participation = ParticipationStatus::where('user_id', $user_id)->orderBy('created_at', 'DESC')->first();
        if(!empty($participation->level_id) && $participation->level_id < 7){
           $status = new ParticipationStatus;
           $status->level_id = $status->level_id + 1;
           $status->user_id = $user_id;
           $status->save();
        }
        $data = [
            'user_id' =>$user_id,
            'comment' => 'Congratutlations. You have been promoted to a new level...',
        ];
      NotificationController::save($data);
    }

     /**
     * This method retrieves
     * 
     *  participation status history
     * 
     * @param $id
     */
    public static function statusHistory($user_id){
        return ParticipationStatus::where('user_id', $user_id)->orderBy('created_at', 'DESC')->get();
    }

      /**
     * This method retrieves
     * 
     * latest participation status history
     * 
     * @param $id
     */
    public static function latestStatusHistory($user_id){
        return ParticipationStatus::where('user_id', $user_id)->orderBy('created_at', 'DESC')->first();
    }

          /**
     * This method retrieves
     * 
     *  participation status history latest
     * 
     * @param $id
     */
    public static function statusLatest($user_id){
        return ParticipationStatus::where('user_id', $user_id)->orderBy('created_at', 'DESC')->latest()->first();
    }

      /**
     * This method retrieves
     * 
     *  participation status
     * 
     * @param $id
     */
    public static function get($id = null){
        if(!empty($id)){
    		return $data = ParticipationStatus::where('id', $id)->orderBy('created_at', 'DESC')->first();
    	}elseif(empty($id)){
    		  return $data = ParticipationStatus::whereNotNull('id')->orderBy('created_at', 'DESC')->paginate(20);
	    	}else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

     /**
     * This method deletes
     * 
     *  participation status
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = ParticipationStatus::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Reward status deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong reward status not deleted successfully. Please try again'
            ]);

        }
    }
}
