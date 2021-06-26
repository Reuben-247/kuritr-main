<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;
use JWTAuth, JWTAuthException;
use App\User;
use App\Admin;
use DB, Validator, Auth;

class AdminAuthController extends Controller
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
        { Config::set('jwt.User', 'App\Admin');
            Config::set('auth.providers.users.model', \App\Admin::class);
            $credentials = ['email' => $request->input('email'), 'password' => $request->input('password'), 'status' => 'approved'];
            $token = null;
            try{
                if(!$token = attempt($credentials)){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid user details',
                    ]);
                }
    
            }catch(JWTAuthException $e){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to authenticate',
                ]);
            }
            return response()->json([
                'status' => 'success',
                'token' => $token,
            ]);
        }
    
        /**
         * Logs out user from the app.
         *
         * @param  \Illuminate\Http\Request  $request
         * 
         * @return \Illuminate\Http\Response
         */
        public function logout(Request $request)
        {
            //
        }
}
