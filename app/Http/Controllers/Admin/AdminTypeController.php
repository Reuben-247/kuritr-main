<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminType;
use DB, Validator, Auth;

class AdminTypeController extends Controller
{
     /**
     * saves a appse settings
     * 
     * @param $request
     * 
     * 
     */
    public static function save( $request){
        $app =  AdminType::create([
              'name' => $request['name'],
              'description' => $request['description'],
              'status' => $request['status'],
          ]);
          return true;
      }
  
  
      /**
       * creates a appse settings
       * 
       * @param $request
       * 
       * 
       */
      public static function create(Request $request){
          $validation = Validator::make($request->all(),
          [
          'name' => 'required',
          "description" => "required",
      
          ]);
          
      if($validation->fails()){
          return $validation->errors();
      }
      $create = self::save($request);
      if($create){
          return response()->json([
             'status' => 'success',
             'message' => 'Admin type added successfully',
          ]);
      }else{
          return response()->json([
              'status' => 'error',
              'message' => 'Something wnet wrong admin type could not be registered. Please try again'
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
              return $data = AdminType::where('id', $id)->first();
          }elseif(empty($id)){
                return $data = AdminType::whereNotNull('id')->paginate(20);
              }else{
            return response()->json([
              'status' => 'error',
              'message' => 'Something went wrong, admin type could not be retrieved'
              ]);
          }
      }
  
      /**
       * This method deletes
       * 
       *  user
       * 
       * @param $id
       */
      public static function delete($id){
          $delete = AdminType::where('id', $id)->delete();
          if($delete){
              return response()->json([ 
                  'status' =>'success',
                  'message' => 'Admin type deleted successfully'
              ]);
  
          }else{
              return response()->json([ 
                  'status' =>'error',
                  'message' => 'Something went wrong admin type not deleted successfully'
              ]);
  
          }
      }
  
      /**
       * This method updates
       * 
       *  a user
       * 
       * @param $id
       */
      public static function update(Request $request){
        
      $update = AdminType::find($request['id']);
  
      if(!empty($request['name'])){
      $update->name = $request['name'];
      }
  
      if(!empty($request['description'])){
          $update->description = $request['description'];
          }
  
  
      $saved = $update->save();
  
      if($saved){
          return response()->json([ 
              'status' =>'success',
              'message' => 'Admin type updated successfully',
              'updated' =>$update
                                   ]);
      }else{
          return response()->json([ 
              'status' =>'error',
              'message' => 'Something went wrong admin type not updated successfully'
          ]);
  
      }
  
  
      }
}
