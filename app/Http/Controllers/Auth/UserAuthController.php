<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB, Auth, Validator;
use Laravel\Passport\Passport;
use App\Http\Controllers\User\LoginTrackController;
use App\Http\Controllers\User\UserController;
use Config;
use JWTAuth, JWTAuthException;
use App\Models\User;

class UserAuthController extends Controller
{
        public function _construct(){
        $this->user = new User;
        $this->admin = new Admin;
        }
        /**
         * Logs the user into app.
         *
         * @param  \Illuminate\Http\Request  $request
         * 
         * @return \Illuminate\Http\Response
         */
        public function login(Request $request)
        {  
            $validation = Validator::make($request->all(),
            [
            
            "password" => "required",
            "email" =>"required|email"
            ,
            ]);
            
        if($validation->fails()){
            return $validation->errors();
        }
            
         //   Config::set('jwt.User', 'App\Models\User');
          //  Config::set('auth.providers.users.model', \App\Models\User::class);
            $credentials = ['email' => $request['email'], 'password' => $request['password'], 'status' => 'active'];
            $credentials_banned = ['email' => $request['email'], 'password' => $request['password'], 'status' => 'banned'];
            $credentials_suspended = ['email' => $request['email'], 'password' => $request['password'], 'status' => 'suspended'];
            $token = null;
            try{
                if(!$token = JWTAuth::attempt($credentials)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid user details',
                    ]);
                }elseif(JWTAuth::attempt($credentials_suspended)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry account suspended please contact the admins',
                    ]);
                }elseif(JWTAuth::attempt($credentials_banned)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Sorry your account has been banned from operating on this paltform.',
                    ]);
                }
    
            }catch(JWTAuthException $e){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to authenticate',
                ]);
            }
            LoginTrackController::save(JWTAuth::user()->id);
            UserController::updateLastLogin(JWTAuth::user()->id);
            return response()->json([
                'status' => 'success',
                'token' => $token,
                'message' => 'Login Succesful',
                'user' => JWTAuth::user()->load('wallet', 'transactionHistory', 'post'),
            ]);
        }
    
        /**
         * Logs out  user from the app
         *
         * @param  \Illuminate\Http\Request  $request
         * 
         * @return \Illuminate\Http\Response
         */
        public function logout()
        {    auth()->logout();
            
      
        }
    
       public static function loginAtReg($request){
       // Config::set('jwt.User', 'App\Models\User');
      //  Config::set('auth.providers.users.model', \App\Models\User::class);
        $credentials = ['email' => $request['email'], 'password' => $request['password'], 'status' => 'active'];
        $token = JWTAuth::attempt($credentials);  
       
        return [
            
            'token' => $token,
            'user' => JWTAuth::user(),
         ];
    
         }
    
    
}
