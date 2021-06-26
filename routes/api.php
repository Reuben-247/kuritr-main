<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Post\PostController;
use App\Http\Controllers\Email\Mailer;
use App\Http\Controllers\Email\EmailVerificationController;
use App\Http\Controllers\Comment\CommentController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Likes\LikeController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Home\HomeController;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// user auth
Route::post('auth/user/login', [UserAuthController::class, 'login']);
Route::get('auth/user/logout', [UserAuthController::class, 'logout']);

// admin auth
Route::post('auth/admin/login', [AdminAuthController::class, 'login']);
Route::get('auth/admin/logout', [AdminAuthController::class, 'logout']);

// home
Route::get('trending-posts', [HomeController::class, 'index']);
Route::get('trending-channels', [HomeController::class, 'trendingChannels']);

// user
Route::post('user/create', [UserController::class, 'create']);
Route::get('user/get/{id?}', [UserController::class, 'get']);
Route::post('user/update', [UserController::class, 'update']);
Route::get('user/delete/{id}', [UserController::class, 'update']);
Route::post('user/change-email', [UserController::class, 'changeEmail']);
Route::post('user/upload-image', [UserController::class, 'uploadImg']);
Route::get('user/unblock/{id}', [UserController::class, 'unblock']);
Route::get('user/ban/{id}', [UserController::class, 'ban']);
Route::get('user/suspend/{id}', [UserController::class, 'suspend']);


Route::get('user/total-user-today', [UserController::class, 'totalUserToday']);
Route::get('user/total-user-this-week', [UserController::class, 'totalUserThisWeek']);
Route::get('user/total-user-this-month', [UserController::class, 'totalUserThisMonth']);
Route::get('user/total-user-this-year', [UserController::class, 'totalUserThisYear']);
Route::get('user/total-user', [UserController::class, 'totalUser']);
Route::get('user/verify-user/{id}', [UserController::class, 'verifyUser']);


// Password reset
Route::post('user/password-reset', [Mailer::class, 'postResetPassword']);

// Email verification
Route::get('user/verify-email/{id}/{code}', [EmailVerificationController::class, 'verify']);
Route::get('user/verify-email/{code}', [EmailVerificationController::class, 'create']);

// post
Route::post('post/create', [PostController::class, 'create']);
Route::get('post/get/{id?}', [PostController::class, 'get']);
Route::post('post/update', [PostController::class, 'update']);
Route::get('post/delete/{id}', [PostController::class, 'delete']);
Route::get('post/show/{id}', [PostController::class, 'show']);
Route::get('post/my-post/{user_id}', [PostController::class, 'myPost']);
Route::get('post/my-ads/{user_id}', [PostController::class, 'myAds']);
Route::get('post/total-post-today', [PostController::class, 'totalPostToday']);
Route::get('post/total-post-this-week', [PostController::class, 'totalPostThisWeek']);
Route::get('post/total-post-this-month', [PostController::class, 'totalPostThisMonth']);
Route::get('post/total-post-this-year', [PostController::class, 'totalPostThisYear']);
Route::get('post/total-post', [PostController::class, 'totalPost']);


// comment
Route::post('post/comment/create', [CommentController::class, 'create']);
Route::get('comment/get/{id?}', [CommentController::class, 'get']);
Route::post('comment/update', [CommentController::class, 'update']);
Route::get('comment/delete/{id}', [CommentController::class, 'delete']);
Route::get('comment/block/{id}', [CommentController::class, 'block']);
Route::get('comment/unblock/{id}', [CommentController::class, 'unblock']);

// reply
Route::post('post/reply/create', [ReplyController::class, 'create']);
Route::get('reply/get/{id?}', [ReplyController::class, 'get']);
Route::post('reply/update', [ReplyController::class, 'update']);
Route::get('reply/delete/{id}', [ReplyController::class, 'delete']);
Route::get('reply/block/{id}', [ReplyController::class, 'block']);
Route::get('reply/unblock/{id}', [ReplyController::class, 'unblock']);


// app setting
Route::post('admin/setting/create', [AppSettingController::class, 'create']);
Route::post('admin/setting/update', [AppSettingController::class, 'update']);
Route::get('admin/setting/get/{id?}', [AppSettingController::class, 'get']);
Route::post('admin/setting/delete/{id}', [AppSettingController::class, 'delete']);



// search
Route::post('get-user-search', [SearchController::class, 'getUserSearch']);
Route::get('search-user/{term}', [SearchController::class, 'searchUser']);
Route::get('search-post/{term}', [SearchController::class, 'searchPost']);
Route::get('search-channel/{term}', [SearchController::class, 'searchChannel']);

// likes
Route::get('like-post/{user_id}/{post_id}/{like_type}', [LikeController::class, 'create']);

// Notiifcations
Route::post('notification/create', [NotificationController::class, 'notify']);
Route::get('notification/get/{id?}', [NotificationController::class, 'get']);
Route::get('notification/user/{id}', [NotificationController::class, 'unread']);
Route::get('notification/mark-as-read/{user_id}/{id?}', [NotificationController::class, 'markAsRead']);
Route::get('notification/delete/{id}', [NotificationController::class, 'delete']);

// Chats
Route::post('chat/create', [ChatController::class, 'create']);
Route::get('chat/get/{id}', [ChatController::class, 'get']);
Route::get('chat/user/{id}', [ChatController::class, 'unread']);
Route::get('chat/user/sent/{id}', [ChatController::class, 'sentMessage']);
Route::get('chat/delete/{id}', [ChatController::class, 'delete']);
Route::post('chat/update', [ChatController::class, 'update']);
Route::get('user/conversation/{receiver_id}/{sender_id}', [ChatController::class, 'conversation']);

