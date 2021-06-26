<?php

namespace App\Http\Controllers\Hit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Post\PostController;
use Illuminate\Http\Request;
use App\Models\Hit;
Use App\Models\Post;
use Carbon\Carbon;
use Session, Validator, DB;

class HitController extends Controller
{
     
      /**
    * saves likes for comment
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $ip = $_SERVER['SERVER_ADDR'];
        $last_date = Carbon::yesterday();
        $check = Hit::where(['ip_address'=> $ip, 'post_id' => $request['post_id']])->where('created_at', '>=', $last_date)->first();
        if(empty($check->id)){
        $create = Hit::create([
        'post_id' => $request['post_id'], 
        'ip_address' => $ip,
        ]);

        $post = Post::where('id', $request['post_id'])->first();
        $post->total_views = $post->total_views + 1;
        $post->total_engagements = $post->total_engagements+1;
        $post->save();
        } 
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
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'post_id' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   // return $request->all();
        $result = self::save($request);
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Liked'

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Liked'
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
    		return $data = Hit::where('id', $id)->with(['post'])->first();
    	}elseif(empty($id)){
    		  return $data = Hit::whereNotNull('id')->with(['post'])->paginate(20);
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
     *  exam type
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = Hit::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Unliked'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong post not unliked successfully'
            ]);

        }
    }

    /**
     * This method checks if liked before
     * 
     * @param $id
     */
    public static function checkHit($user_id){
      
    $like = Hit::where('user_id', $user_id)->first();
    if($like->user_id){
        return true;
    }else{
        return false;
         }
    }
}
