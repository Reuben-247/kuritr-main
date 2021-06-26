<?php

namespace App\Http\Controllers\Image;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File, Image, DB, Cloudinary;

class ImageController extends Controller
{
     /**
     * @access public
     *
     * @static
     * 
     * @var $requuest
     * 
     * @return bool
     */
    public static function avatar(Request $request)
    {
        if ($request->hasFile('avatar'))
        {    
            $file = $request->file('avatar');
            $filename = time().rand().'-'.$file->getClientOriginalExtension();
            //   $path = public_path('images/'.$user.'/'.$filename);
            $avatar = Image::make($file->getRealPath())->resize(100, 120)->stream();
            $avatar = $avatar->__toString();
            $s3 = \Storage::disk('s3');
            return  $s3->put('/'.$filename, $avatar, 'public');
         
        }
        else{
            return false;
        }

    }

     /**
     * Saves an image to cloudinary
     *
     * and returns the url
     * 
     * @var $image
     * 
     * @return string
     */
    public static function uploadAvatar($image){
        if(!empty($image)){
      $compressedImg =  Cloudinary::upload($image->getRealPath(),
        [ 'folder' => 'avatar',
        'transformation' =>
        ['width' => 100,
        'height' =>100,
        'quality'=> auto,
        'fetch_format' => auto,
        ]
        ])->getSecurePath();
         return $compressedImg;
        }else{
            return false;
        }
    }

     /**
     * Saves a post image to cloudinary
     *
     * and returns the url
     * 
     * @var $image
     * 
     * @return string
     */
    public static function uploadPostImg($image){
        if(!empty($image)){
      $compressedImg =  Cloudinary::upload($image->getRealPath(),

        [ 'folder' => 'post',
        'transformation' =>

        [ 'width' => 400,
          'height' =>300,
          'quality'=> auto,
          'fetch_format' => auto,
           ]
                ])->getSecurePath();
            return $compressedImg;
           }else{
            return false;
        }
    }

       /**
     * Saves a post image to cloudinary
     *
     * and returns the url
     * 
     * @var $image
     * 
     * @return string
     */
    public static function uploadChannelImg($image){
        if(!empty($image)){
      $compressedImg =  Cloudinary::upload($image->getRealPath(),

        [ 'folder' => 'channel',
        'transformation' =>

        [ 'width' => 400,
          'height' =>300,
          'quality'=> auto,
          'fetch_format' => auto,
           ]
                ])->getSecurePath();
            return $compressedImg;
           }else{
            return false;
        }
    }

        /**
     * @access public
     *
     * @static
     * 
     * @var $requuest
     * 
     * @return bool
     */
    public static function postImageUpload($image)
    {
        if (!empty($image))
        {    
            $file = $image;//$request->file('image');
            $filename = rand().time().'.'.$file->getClientOriginalExtension();
           // $path = public_path('images/post/'.$filename);
            $width = Image::make($file->getRealPath())->width();
            $height = Image::make($file->getRealPath())->height();
            if($width > 400 && $height > 250){
           $upload = Image::make($file->getRealPath())->resize(400, 210)->stream();
           //  return $filename;
           }else{
           $upload = Image::make($file->getRealPath())->stream();
             // return $filename;
          }
          $upload = $upload->__toString();
          $s3 = \Storage::disk('s3');
           $s3->put('/'.$filename, $upload, 'public');
           return $filename;
        }
        else{
            return false;
        }

    }

    

     /**
      * This method checks if a particlar
      * user has logo and deletes it from DB
      * and also deltes it from folder
      *
     * @access public
     *
     * @static
     * 
     * @var $file
     * 
     * @return image name
     */
      public static function deleteAvatar($id)
      {
        $image = UserImage::where('user_id', $id)->first();
        if(!empty($image))
        {   unlink('images/user/'.$image->avatar);
            $image->delete();
            return true;
        }else{
            return true;
        }
      }

      
     /**
      * This method checks if a particlar
      * post has an image and deletes it from DB
      * and also deltes it from folder
      *
     * @access public
     *
     * @static
     * 
     * @var $file
     * 
     * @return image name
     */
    public static function deleteImage($id)
    {
      $image = Image::where('post_id', $id)->get();
      if(!empty($image))
      { 
          foreach($image as $img) {
           unlink('images/post/'.$img->image);
          $image->delete();
          }
          return true;
      }else{
          return true;
      }
    }
}
