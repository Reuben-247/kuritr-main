<?php

namespace App\Http\Controllers\Reply;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Reply;
use DB, Validator, Auth;

class ReplyController extends Controller
{
    
      /**
    * saves reply
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = Reply::create([
            
            'user_id' => $request['user_id'],
            'comment_id' => $request['comment_id'],
            'reply' => $request['reply'],
           
        ]);
        
       // return $create->id;

    }
    /**
     * This method creates 
     * 
     * reply
     * 
     * @param $request
     * 
     * @return response
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'reply' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   // return $request->all();
        $result = self::save($request);
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Reply created successfully'

                    ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Reply not created successfully'
            ]);

        }

    }

     /**
     * This method retrieves
     * 
     *  reply(s)
     * 
     * @param $id
     * 
     * @return response
     */
    public static function get($id = null){
        if(!empty($id)){
    		return $data = Reply::where('id', $id)->with(['comment', 'post'])->first();
    	}elseif(empty($id)){
    		  return $data = Reply::whereNotNull('id')->with(['post', 'comment'])->paginate(20);
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
     *  reply(s)
     * 
     * @param $id
     * 
     * @return response
     */
    public static function block($id){
        if(!empty($id)){
             $data = Reply::where('id', $id)->with(['post'])->first();
             $data->status = "block";
             $data->save();

             return response()->json([
                'status' => 'success',
                'message' => 'reply blocked successfully',
                ]);
    	}
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, reply could not be blocked. Please try again.',
            ]);

    }

    /**
     * This method deletes
     * 
     *  delete
     * 
     * @param $id
     * 
     * @return response
     */
    public static function delete($id){
        $delete = Reply::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Reply deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong reply not deleted successfully'
            ]);

        }
    }

    /**
     * This method updates
     * 
     *  reply
     * 
     * @param $id
     * 
     * @return response
     */
    public static function update(Request $request){
      
    $update = Reply::find($request['id']);

    if(!empty($request['reply'])){
    $update->reply = $request['reply'];
    }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Reply updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong reply not updated successfully'
              ]);

           }

     }

}
