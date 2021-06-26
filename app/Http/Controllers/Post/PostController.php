<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Image\ImageController;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\PostImage;
use App\Models\NotificationController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB, Validator, Auth;

class PostController extends Controller
{
    
     /**
    * saves post details
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $user = User::where('id', $request['user_id'])->first();
        $create = new Post;
        $create->user_id = $request['user_id'];
        $create->channel_id = $request['channel_id'];
        if($user->user_type_id == 3){
            $create->post =   $request['post'];
        }elseif($user->user_type_id == 4){
            $create->post = $request['post'];
        }else{
            $create->post = $request['post']; 
        }
        $create->title = $request['title'];
        $create->post_position = $request['post_position'];
        $create->sponsored = 'no';
        $create->save();
        
        return $create->id;

    }

   

    /**
     * This method creates 
     * 
     * post
     * 
     * @param $request
     * 
     * @return response
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(),
        [
         'user_id' => 'required',
         'channel_id' => 'required',
         'post' => 'required',
         'title' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
   // return $request->all();
        $result = self::save($request);
        if($result){
            if(!empty($request->file('images'))){
                foreach($request->file('images') as $img){
                    $postimg = new PostImage;
                    $postimg->post_id = $result;
                    $postimg->name = ImageController::uploadPostImg($img);
                    $postimg->save();
                }
            }
            return response()->json([ 
                'status' =>'success',
                'message' => 'Post created successfully'

                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Post not created successfully'
            ]);

        }

    }

    /**
     * saves a post image
     * 
     * @param $request
     * 
     * 
     */
    public static function uploadImg($request){
        if(!empty($request->file('image'))){
            foreach($request->file('image') as $img){
                $postimg = new PostImage;
                $postimg->post_id = $request['post_id'];
                $postimg->name = ImageController::uploadPostImg($img);
                $postimg->save();
            }
      
        return response()->json([ 
            'status' =>'success',
            'message' => 'Image posted successfully'

                                 ]);
            }else{
                return response()->json([ 
                    'status' =>'error',
                    'message' => 'Something went wrong Image not posted successfully. Please try again'
        
                         ]); 
            }
    }

      /**
     * This method retrieves
     * 
     *  category
     * 
     * @param $id
     */
    public static function approve($id){
        if(!empty($id)){
    		 $data = Post::where('id', $id)->first();
             $data->status = 'active';
             $data->save();
             $data = ['user_id' => $data->user_id,
             'comment' => 'Your post has been approved'
                 ];
             NotificationController::save($approve);
             return response()->json([
                'status' => 'success',
                'message' => 'Post has been approved'
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved'
            ]);
        }
    }

   /**
     * This method deline
     * 
     *  category
     * 
     * @param $id
     */
    public static function decline($id){
        if(!empty($id)){
    		 $data = Post::where('id', $id)->first();
             $data->status = 'declined';
             $data->save();
             $decline = ['user_id' => $data->user_id,
             'comment' => 'We are sorry to inform you that your post has been declined'
                 ];
            NotificationController::save($decline);
             return response()->json([
                'status' => 'success',
                'message' => 'Post has been declined',
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved',
            ]);
        }
    }

    /**
     * This method retrieves
     * 
     *  category
     * 
     * @param $id
     */
    public static function block($id){
        if(!empty($id)){
    		 $data = Post::where('id', $id)->first();
             $data->status = 'block';
             $data->save();
             $block = ['user_id' => $data->user_id,
             'comment' => 'Your post has been blocked',
                 ];
             NotificationController::save($block);
             return response()->json([
                'status' => 'success',
                'message' => 'Post has been suspended',
                ]);
	    	}else{
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved',
            ]);
        }
    }

       /**
     * This method blocks a post
     * 
     *  comment(s)
     * 
     * @param $id
     */
    public static function unblock($id){
        if(!empty($id)){
             $data = Post::where('id', $id)->first();
             $data->status = "active";
             $data->save();
             $unblock = ['user_id' => $data->user_id,
             'comment' => 'Your post has been unblocked',
                 ];
            NotificationController::save($unblock);
             return response()->json([
                'status' => 'success',
                'message' => 'Post blocked successfully',
                ]);
    	}
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, post could not be retrieved',
            ]);

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
    		return $data = Post::where(['id' => $id, 'status' => 'approved'])->with(['user', 'comment'])->first();
    	}elseif(empty($id)){
    		  return $data = Post::whereNotNull('id')->where('status', 'approved')->with(['user', 'comment'])->paginate(20);
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
     *  a single post
     * 
     * @param $id
     */
    public static function show($id){
        if(!empty($id)){
            $data = ['post_id' => $id];   
        return Post::where('id', $id)->with(['postimage', 'comment'])->first();
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
        $delete = Post::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Post deleted successfully'
                ]);
          }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong post not deleted successfully'
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
      
    $update = Post::find($request['id']);

    if(!empty($request['title'])){
        $update->title = $request['title'];
        }

    if(!empty($request['post'])){
    $update->post = $request['post'];
    }

     if(!empty($request['channel_id'])){
            $update->channel_id = $request['channel_id'];
            }
    if(!empty($request['sponsored'])){
                $update->sponsored = $request['sponsored'];
                }
     if(!empty($request['status'])){
                    $update->status = $request['status'];
                    }


    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Post updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong post not updated successfully'
        ]);

    }


    }



/**
 * returns my post
 * @param $user_id
 * 
 * @return object
 */
public function myPost($user_id){
    if(!empty($user_id)){
        $data = Post::where(['id'=> $user_id, 'status'=>'approved'])->with(['postimage'])->orderBy('created_at', 'DESC')->paginate(25);
        if(!$data->isEmpty()){
            return $data;
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'You do not hane any post yet. Please make posts',
            ]);  
        }
    }else{
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, post could not be retrieved. Please try again',
        ]);
    }
}

/**
 * gets the total number of
 * posts today
 * 
 * @return response
 * 
 */
public static function totalPostToday(){
    $now = Carbon::today();
    return Post::where('created_at', $now)->count();
}

/**
 * gets the total number of
 * posts this week
 * 
 * @return response
 * 
 */
public static function totalPostThisWeek(){
    $now = Carbon::today();
    $weekstart = $now->startOfWeek();
    return Post::whereDate('created_at', '>=', $weekstart)->count();
}


/**
 * gets the total number of
 * posts this month
 * 
 * @return response
 * 
 */
public static function totalPostThisMonth(){
    $now = Carbon::today();
    $monthstart = $now->startOfMonth();
    return Post::whereDate('created_at', '>=', $monthstart)->count();
}

/**
 * gets the total number of
 * posts this year
 * 
 * @return response
 * 
 */
public static function totalPostThisYear(){
    $now = Carbon::now();
    $yearstart = $now->startOfYear();
    return Post::whereDate('created_at', '>=', $yearstart)->count();
}

/**
 * gets the total number of
 * posts on the platform
 * 
 * @return response
 * 
 */
public static function totalPost(){
    return Post::whereNotNull('id')->count();
}

}
