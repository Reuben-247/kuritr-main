<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use DB, Validator, Auth;

class ChatController extends Controller
{
     
      /**
    * saves notification
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = Chat::create([        
            'sender_id' => $request['sender_id'], // sender of the message
            'receiver_id' => $request['receiver_id'], // the receiver of the message
            'msg' => $request['msg'],
            'status' => 'unread',  
        ]);
        
        return true;

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
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'msg' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   // return $request->all();
        $result = self::save($request);
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Message sent successfully'

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Message not sent successfully. Please try again'
            ]);

        }

    }

     /**
     * This method retrieves
     * 
     *  unread message
     * 
     * @param $receiver_id
     */
    public static function unread($receiver_id){
      return $data = User::where(['id' => $receiver_id])->with(['chat'])->orderBy('created_at', 'DESC')->get();
    }

     /**
     * This method retrieves
     * 
     *  a two way chat between two users
     * 
     * @param $receiver_id, $sender_id
     */
    public static function conversation($receiver_id, $sender_id){
        return $data = Chat::where(['receiver_id' => $receiver_id, 'sender_id' => $sender_id, 'status' => 'unread'])->with(['sender'])->orderBy('created_at', 'DESC')->get();
      }

       /**
     * This method retrieves
     * 
     *  sent message
     * 
     * @param $id
     */
    public static function sentMessage($sender_id){
        return $data = Chat::where(['sender_id' => $sender_id])->with(['receiver'])->orderBy('created_at', 'DESC')->get();
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
    		return $data = Chat::where(['id' => $id])->orderBy('created_at', 'DESC')->first();
    	}elseif(empty($id)){
    		  return $data = Chat::whereNotNull('id')->paginate(20);
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
    public static function markAsRead($receiver_id, $id = Null){
        if(!empty($id)){
             $data = Chat::where(['id' => $id])->first();
             $data->status = "read";
             $data->save();

             return response()->json([
                'status' => 'success',
                'message' => 'Message marked as read successfully',
                ]);
    	}elseif(empty($id)){
            $data = Chat::where(['receiver_id' => $receiver_id, 'status'=>'unread'])->get();
            foreach($data as $dt){
                $dt->status = 'read';
                $dt->save();
            }
            return response()->json([
                'status' => 'success',
                'message' => 'All your unread messages marked as read successfully',
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
        $delete = Chat::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Message deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong message not deleted successfully'
            ]);

        }
    }

    /**
     * This method updates
     * 
     *  exam type
     * 
     * @param $request
     */
    public static function update(Request $request){
      
    $update = Chat::find($request['id']);

    if(!empty($request['msg'])){
    $update->msg = $request['msg'];
    }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Message updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong message not updated successfully'
           ]);
      }
}


}
