<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Image;
use App\Models\Userimage;

trait UploadTrait
{
    /**
     * Upload image
     * @param
     * @return \Illuminate\Http\Response
     */
    public function uploadOne($file, $image_path)
    {
        //create directory if not exists

        if(!File::isDirectory($image_path)){

            File::makeDirectory($image_path, 0777, true, true);
        }

        $salt_image  = time().rand(1111, 9999);
        //$salt_image  = bin2hex(openssl_random_pseudo_bytes(22));

        $image_name = $salt_image.'.'.$file->getClientOriginalExtension();

        $file->move($image_path, $image_name);

        return $image_name;
    }

    /**
     * Upload image with compress
     * @param
     * @return \Illuminate\Http\Response
     */
    public function uploadimageCompress($file, $image_path)
    {
        // open an image file
        $get_image_size = getimagesize($file->getRealPath());
        $width = $get_image_size[0];
        $height = $get_image_size[1];
        $canvas = Image::canvas($width, $height);
        $thumb_image = Image::make($file->getRealPath())->resize($width, $canvas, function($constraint){
            $constraint->aspectRatio();
        });

        $canvas->insert($thumb_image, 'fit');

        $salt_image  = bin2hex(openssl_random_pseudo_bytes(22));

        $image_name = $salt_image.'.'.$file->getClientOriginalExtension();

        // now you are able to resize the instance
        $thumb_image->save($image_path.'/'.$image_name, 80);

        return $image_name;

    }

    /**
     * Upload image
     * @param
     * @return \Illuminate\Http\Response
     */
    public function uploadProfile($file, $image_path)
    {
        if(!File::isDirectory($image_path)) {

            File::makeDirectory($image_path, 0777, true, true);
        }

        // open an image file
        $get_image_size = getimagesize($file->getRealPath());
        $width = $get_image_size[0];
        $height = $get_image_size[1];
        $canvas = Image::canvas($width, $height);
        $thumb_image = Image::make($file->getRealPath())->resize($width, $canvas, function($constraint){
            $constraint->aspectRatio();
        });

        $canvas->insert($thumb_image, 'fit');

        $salt_image  = bin2hex(openssl_random_pseudo_bytes(22));

        $image_name = $salt_image.'.'.$file->getClientOriginalExtension();

        // now you are able to resize the instance
        $thumb_image->save($image_path.'/'.$image_name, 80);

        return $image_name;
    }

}
