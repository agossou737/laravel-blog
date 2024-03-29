<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function savedImage($image, $path="public"){
       

        $filename = time()."flutter_blog.png";

        //save image


        Storage::disk($path)->put($filename, base64_decode($image));

        return URL::to("/").'/storage/'.$path.'/'.$filename;
    }
}
