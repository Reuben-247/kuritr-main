<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppSetting;
use Validator;


class AppSettingController extends Controller
{   
    public static $bonus = 200;

    
    /**
     * saves a appse settings
     * 
     * @param $request
     * 
     * 
     */
    public static function save( $request){
        $app =  AppSetting::create([
              'name' => $request['name'],
              'value' => $request['value'],
              'access_level' => $request['access_level'],
              'status' => $request['status'],
          ]);
          return true;
      }
  
       /**
       * returns initial bonus
       * for everyone who signs up on 
       *  the platform
       */
      public static function getInitialBonus(){
          $bonus = AppSetting::where('access_level', 'initial')->where('status', 'active')->first();
          if(!empty($bonus->value)){
              return $bonus->value;
          }else{
              return self::$bous;
          }
  
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
          "value" => "required",
      
          ]);
          
      if($validation->fails()){
          return $validation->errors();
      }
      $create = self::save($request);
      if($create){
          return response()->json([
             'status' => 'success',
             'message' => 'Settings added successfully',
          ]);
      }else{
          return response()->json([
              'status' => 'error',
              'message' => 'Something wnet wrong setting could not be registered. Please try again'
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
              return $data = AppSetting::where('id', $id)->first();
          }elseif(empty($id)){
                return $data = AppSetting::whereNotNull('id')->paginate(20);
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
       *  user
       * 
       * @param $id
       */
      public static function delete($id){
          $delete = AppSetting::where('id', $id)->delete();
          if($delete){
              return response()->json([ 
                  'status' =>'success',
                  'message' => 'Setting deleted successfully'
              ]);
  
          }else{
              return response()->json([ 
                  'status' =>'error',
                  'message' => 'Something went wrong setting not deleted successfully'
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
        
      $update = AppSetting::find($request['id']);
  
      if(!empty($request['name'])){
      $update->name = $request['name'];
      }
  
    
  
      if(!empty($request['value'])){
          $update->value = $request['value'];
          }
  
      if(!empty($request['access_level'])){
          $update->access_level = $request['access_level'];
                  }
      if(!empty($request['status'])){
          $update->status = $request['status'];
              }
  
      $saved = $update->save();
  
      if($saved){
          return response()->json([ 
              'status' =>'success',
              'message' => 'Settings updated successfully',
              'updated' =>$update
                                   ]);
      }else{
          return response()->json([ 
              'status' =>'error',
              'message' => 'Something went wrong setting not updated successfully'
          ]);
  
      }
  
  
      }
}
