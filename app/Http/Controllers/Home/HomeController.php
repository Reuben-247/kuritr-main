<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Comment;
use App\Models\Channel;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * This method returns home page
     * 
     * returns posts and are ranked based on their post engagements
     * 
     * posts
     * 
     * @return collection
     * 
     */
    public static function index(){
        $day = Carbon::now();
        $yesterday = $day->startOfMonth();
     $post = Post::whereDate('created_at', '>=', $yesterday)->where('status', 'approved')->orderBy('total_engagements', 'DESC')
                    ->orderBy('created_at', 'DESC')->with(['postimage', 'comment'])->paginate(25)->unique('title');

     return $post;
    }

    /**
     * This method returns trending channels
     * 
     * 
     * @return collection
     * 
     * 
     */
    public function trendingChannels(){
        $day = Carbon::now();
        $yesterday = $day->startOfMonth();
      $posts =  Post::whereDate('created_at', '>=', $yesterday)->where('status', 'approved')->orderBy('total_engagements', 'DESC')
                                    ->paginate(30)->unique('channel_id');
        $channel_id = [];
      foreach($posts as $post){
          $channel_id[] = $post->channel_id;
      }
      return Channel::whereIn('id', $channel_id)->limit(10)->get();

    }



}
