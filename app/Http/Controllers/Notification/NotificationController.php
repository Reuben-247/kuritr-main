<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use DB, Validator, Auth;

class NotificationController extends Controller
{
    
    
      /**
    * saves notification
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = Notification::create([
            
            'user_id' => $request['user_id'],
            'comment' => $request['comment'],
            'status' => 'unread',
           
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
    public function notify(Request $request){
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
                'message' => 'Notification sent successfully'

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Notification not created successfully'
            ]);

        }

    }

     /**
     * This method retrieves
     * 
     *  unread message
     * 
     * @param $id
     */
    public static function unread($user_id){
      return $data = Notification::where(['user_id' => $user_id, 'status' => 'unread'])->orderBy('created_at', 'DESC')->get();
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
    		return $data = Notification::where(['id' => $id, 'status' => 'unread'])->first();
    	}elseif(empty($id)){
    		  return $data = Notification::whereNotNull('id')->paginate(20);
	    	}else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

     /**
     * This method blocks a comment
     * 
     *  comment(s)
     * 
     * @param $id
     */
    public static function markAsRead($user_id, $id = null){
        if(!empty($id)){
             $data = Notification::where(['id' => $id, 'user_id' => $user_id])->first();
             $data->status = "read";
             $data->save();

             return response()->json([
                'status' => 'success',
                'message' => 'Notification marked as read successfully',
                ]);
    	}elseif(empty($id)){
            $data = Notification::where(['user_id' => $user_id])->get();
            foreach($data as $dt){
                $dt->status = 'read';
                $dt->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'All notifications marked as read successfully',
                ]);
        }else{
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved',
            ]);
        }

    }

    /**
     * This method deletes
     * 
     *  exam type
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = Notification::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Notification deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong notification not deleted successfully'
            ]);

        }
    }

    /**
     * This method updates
     * 
     *  exam type
     * 
     * @param $id
     */
    public static function update(Request $request){
      
    $update = Notification::find($request['id']);

    if(!empty($request['comment'])){
    $update->comment = $request['comment'];
    }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Notification updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong notification not updated successfully'
        ]);

    }


    }

}
