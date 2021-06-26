<?php

namespace App\Http\Controllers\ParticipationReward;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParticipationReward;
use carbon\Carbon;
use DB, Validator, Auth;


class ParticipationRewardController extends Controller
{
       /**
    * saves likes for comment
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request, $point){
        $create = ParticipationReward::create([
            
            'user_id' => $request['user_id'],
            'activity_id' => $request['activity_id'],
            'activity' => $request['activity'],
            'point' => $point,
           
        ]);
        
       // return $create->id;

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
    public function create($request, $point){
             
  
   // return $request->all();
        $result = self::save($request, $point);
      
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Participation created successfully',

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Participation reward not created successfully. please try again',
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
    		return $data = ParticipationReward::where('id', $id)->first();
    	}elseif(empty($id)){
    		  return $data = ParticipationReward::whereNotNull('id')->paginate(20);
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
     *  reward total today
     * 
     * @param $id
     */
    public static function getTodayTotal(){
        $today = Carbon::now();
       // $monthstart = $now->startOfMonth();
       return ParticipationReward::whereBetween('created_at', $today)->with(['user'])->sum('point');

    }

      /**
     * This method retrieves
     * 
     *  reward total since this week
     * 
     * @param $id
     */
    public static function getThisWeekTotal(){
        $now = Carbon::now();
        $weekstart = $now->startOfWeek();
       return ParticipationReward::whereBetween('created_at', [$weekstart, $today])->with(['user'])->sum('point');

    }

     /**
     * This method retrieves
     * 
     *  reward total since this month
     * 
     * @param $id
     */
    public static function getThisMonthTotal(){
        $now = Carbon::now();
        $monthstart = $now->startOfMonth();
       return ParticipationReward::whereBetween('created_at', [$monthstart, $today])->with(['user'])->sum('point');

    }

       /**
     * This method retrieves
     * 
     *  reward total since this year
     * 
     * @param $id
     */
    public static function getThisYearTotal(){
        $now = Carbon::now();
        $yearstart = $now->startOfYear();
       return ParticipationReward::whereBetween('created_at', [$yearstart, $today])->with(['user'])->sum('point');

    }

    /**
     * This method deletes
     * 
     *  exam type
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = ParticipationReward::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Reward deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong reward not deleted successfully'
            ]);

        }
    }

   

    
}
