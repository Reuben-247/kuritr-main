<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\User\UserActivityLogController;
use Illuminate\Http\Request;
use App\Models\Comment;
use Validator;
use Carbon\Carbon;

class CommentController extends Controller
{
  
      /**
    * saves examtype details
    *
    * @param $request
    *
    * @return id
     */
    public static function save($request){
        $create = Comment::create([
      
            'user_id' => $request['user_id'],
            'post_id' => $request['post_id'],
            'comment' => $request['comment'],
           
        ]);
        
        return $create->id;

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
         'comment' => 'required',
        ]);
        
    if($validation->fails()){
        return $validation->errors();
    }
  
        $result = self::save($request);
        $data = [
            'post_id' => $request['post_id'],
            'user_id' => $request['user_id'],
            'activity_id' => $result,
            'activity_model' => 'Comment',
            'action' => 'commenting',
            'description' => 'a comment for a post',
           ];
        $total_comment = 'total_comment';
        PostController::engagementCounter($request['post_id'], $total_comment);
        UserActivityLogController::save($data);
        if(!empty($result)){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Comment created successfully'

                    ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Comment not created successfully'
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
    		return $data = Comment::where('id', $id)->with(['post'])->first();
    	}elseif(empty($id)){
    		  return $data = Comment::whereNotNull('id')->with(['post'])->paginate(20);
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
    public static function block($id){
        if(!empty($id)){
             $data = Comment::where('id', $id)->with(['post'])->first();
             $data->status = "block";
             $data->save();

             return response()->json([
                'status' => 'success',
                'message' => 'Comment blocked successfully',
                ]);
    	}
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, data could not be retrieved',
            ]);

    }

    /**
     * This method unblocks a comment
     * 
     *  comment(s)
     * 
     * @param $id
     */
    public static function unblock($id){
        if(!empty($id)){
             $data = Comment::where('id', $id)->with(['post'])->first();
             $data->status = "unblock";
             $data->save();

             return response()->json([
                'status' => 'success',
                'message' => 'Comment unblocked successfully',
                ]);
    	}
          return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, comment could be unblocked',
            ]);

    }

    /**
     * This method deletes
     * 
     *  exam type
     * 
     * @param $id
     */
    public static function delete($id){
        $delete = Comment::where('id', $id)->delete();
        if($delete){
            return response()->json([ 
                'status' =>'success',
                'message' => 'Comment deleted successfully'
               
                                     ]);
        }else{
            return response()->json([ 
                'status' =>'error',
                'message' => 'Something went wrong comment not deleted successfully'
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
      
    $update = Comment::find($request['id']);

    if(!empty($request['comment'])){
    $update->comment = $request['comment'];
    }

    $saved = $update->save();

    if($saved){
        return response()->json([ 
            'status' =>'success',
            'message' => 'Comment updated successfully',
            'updated' =>$update
                                 ]);
    }else{
        return response()->json([ 
            'status' =>'error',
            'message' => 'Something went wrong comment not updated successfully'
        ]);

    }


    }



    /**
 * gets the total number of
 * comment today
 * 
 * @return response
 * 
 */
public static function totalCommentToday(){
    $now = Carbon::now();
    return Comment::where('created_at', $now)->count();
}

/**
 * gets the total number of
 * comment this week
 * 
 * @return response
 * 
 */
public static function totalCommentThisWeek(){
    $now = Carbon::now();
    $weekstart = $now->startOfWeek();
    return Comment::whereBetween('created_at', [$weekstart, $now])->count();
}


/**
 * gets the total number of
 * posts this month
 * 
 * @return response
 * 
 */
public static function totalCommentThisMonth(){
    $now = Carbon::now();
    $monthstart = $now->startOfMonth();
    return Comment::whereBetween('created_at', [$monthstart, $now])->count();
}

/**
 * gets the total number of
 * posts this year
 * 
 * @return response
 * 
 */
public static function totalCommentThisYear(){
    $now = Carbon::now();
    $yearstart = $now->startOfMonth();
    return Comment::whereBetween('created_at', [$yearstart, $now])->count();
}

/**
 * gets the total number of
 * posts on the platform
 * 
 * @return response
 * 
 */
public static function totalComment(){
    return Comment::whereNotNull('id')->count();
}
  

}
