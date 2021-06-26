<?php

namespace App\Http\Controllers\Likes;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Post\PostController;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\User;
use App\Models\Comment;
use Validator;
use Carbon\Carbon;

class LikeController extends Controller
{
    
      /**
    * saves likes for comment
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = Like::create([
            
            'user_id' => $request['user_id'],
            'post_id' => $request['post_id'],
            'status' => $request['status'],
           
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
    public function create($user_id, $post_id, $like_type){
              $data = [
                  'user_id' =>$user_id,
                  'post_id' => $post_id,
                  'status' => $like_type,

              ];
   $check =  self::checkLike($user_id, $post_id);
   if($check == false){
        $result = self::save($data);
        $value = 'total_'.$like_type;
        PostController::engagementCounter($post_id, $value);
        if($result){
            return response()->json([ 
                'status' =>'success',
                'message' => 'You '.$like_type.' with the view point on this post',

                        ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Not '.$like_type .'d .Please try again',
              ]);
        }
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'You have liked this post before',
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
    		return $data = Like::where('id', $id)->with(['post'])->first();
    	}elseif(empty($id)){
    		  return $data = Like::whereNotNull('id')->with(['post'])->paginate(20);
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
        $delete = Like::where('id', $id)->delete();
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
    public static function checkLike($user_id, $post_id){
    $yesterday = Carbon::yesterday();
    $like = Like::where(['user_id' => $user_id, 'post_id' => $post_id])->whereDate('created_at', '>', $yesterday)->first();
    if(!empty($like->id)){
        return true;
    }else{
        return false;
         }
    }

}
