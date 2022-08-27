<?php


namespace App\Helpers;


use Illuminate\Support\Facades\File;

class ImgHelper
{

    public static function uploadImage($folder, $image)
    {
        $image->store('/', $folder);
        $filename = 'uploads/'.$image->hashName();
        return $filename;
    }

    public static function deleteImage($folder,$oldImg)
    {
       try{
           \Illuminate\Support\Facades\Storage::disk($folder)->delete($oldImg);

       }catch (\Exception $exception){

       }
    }

    public static function returnImageLink($obj){

        $path = $obj;
        if(is_object(json_decode($obj))){
            $obj  = json_decode($obj);

            if(isset($obj->path)){
                $path = $obj->path;
            }
        }

        if(File::exists($path)){
            return url($path);
        }

        return url("/images/default_ad_img.jpg");

    }

    public static function returnSliderLinks($links){


        $returnLinks = [];
        $links = json_decode($links, true);
        foreach ($links as $link){
            $returnLinks[] = self::returnImageLink($link);
        }

        return $returnLinks;

    }

}
