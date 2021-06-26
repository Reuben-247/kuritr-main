<?php

namespace App\Http\Controllers\Advert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdsRate;
use DB, Validator, Auth;

class AdsRateController extends Controller
{
    
     /**
    * saves ads rate details
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = AdsRate::create([
            
            'daily_amount' => $request['daily_amount'],
            'estimated_views' => $request['estimated_views'],
            'ads_name' => $request['ads_name'],
            'ads_location' => $request['ads_location'],
            'description' => $request['description'],
           
        ]);
        
        return $create->id;

    }
    /**
     * This method creates 
     * 
     * eads rate
     * 
     * @param $request
     * 
     * @return response
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'comment' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   // return $request->all();
        $result = self::save($request);
       
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Ads rate created successfully'

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Ads rate not created successfully'
            ]);

        }

    }

     /**
     * This method retrieves
     * 
     *  ad rate(s)
     * 
     * @param $id
     */
    public static function get($id = null){
        if(!empty($id)){
    		return $data = AdsRate::where('id', $id)->with(['post'])->first();
    	}elseif(empty($id)){
    		  return $data = AdsRate::whereNotNull('id')->with(['post'])->paginate(20);
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
     *  ads rate
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = AdsRate::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Ads rate deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong ads rate not deleted successfully'
            ]);

        }
    }

    /**
     * This method updates
     * 
     *  ads rate
     * 
     * @param $request
     */
    public static function update(Request $request){
      
    $update = AdsRate::find($request['id']);

    if(!empty($request['ads_name'])){
    $update->ads_name = $request['ads_name'];
    }

    if(!empty($request['ads_location'])){
        $update->ads_location = $request['ads_location'];
        }
     if(!empty($request['description'])){
            $update->description = $request['description'];
            }
    if(!empty($request['daily_amount'])){
                $update->daily_amount = $request['daily_amount'];
                }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Ads rate updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong ads rate not updated successfully'
        ]);

       }
  }


}
