<?php
namespace App\Lib;
use Illuminate\Database\Eloquent\Model;
use Image;
use Illuminate\Support\Facades\Storage;
/**
 * 
 * 
 * This Library use for image upload and resizing.
 *  
 * 
 **/

class Uploader
{
    
    public static function doUpload($file,$path,$thumb=false,$pre=null,$id=null){
        $response = [];
        $image = $file;
        if($id!=null){
            $file = $id.'.'.$image->getClientOriginalExtension();
        }else{
            $file = $pre.time().'.'.$image->getClientOriginalExtension();
        }
        $destinationPath = public_path().'/'.$path; 
        Image::make($image)->save($destinationPath.$file,70);
        $thumbPath = '';
        if($thumb==true){
            $thumbPath = public_path($path).'thumb/'.$file;
            if(!file_exists(public_path($path).'thumb/')) {
              mkdir(public_path($path).'thumb/', 0777, true);
            }
            $cropInfo = Image::make($image)->heighten(200)->save($thumbPath);
        }
        $response['status']     = true;
        $response['file']       = "public".$path.$file;
        $response['thumb']       = "public".$path."thumb/".$file;
        $response['file_name']  = $file;
        $response['path']       = $path; 
        return $response;

    }


    public static function doBannerUpload($file,$path,$thumb=false,$pre=null,$id=null){
        $response = [];
        $image = $file;
        if($id!=null){
            $file = $id.'.'.$image->getClientOriginalExtension();
        }else{
            $file = $pre.time().'.'.$image->getClientOriginalExtension();
        }
        $destinationPath = public_path().'/'.$path; 
        Image::make($image)->resize(300,144)->save($destinationPath.$file,70);
        $thumbPath = '';
        if($thumb==true){
            $thumbPath = public_path($path).'thumb/'.$file;
            if(!file_exists(public_path($path).'thumb/')) {
              mkdir(public_path($path).'thumb/', 0777, true);
            }
            $cropInfo = Image::make($image)->heighten(200)->save($thumbPath);
        }
        $response['status']     = true;
        $response['file']       = "public".$path.$file;
        $response['thumb']       = "public".$path."thumb/".$file;
        $response['file_name']  = $file;
        $response['path']       = $path; 
        return $response;

    }
    
}
