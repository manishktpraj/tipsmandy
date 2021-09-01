<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait CurlTrait
{

    protected function rapidapiCurl(array $data)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $data['rapidapiUrl'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: ".$data['rapidapiHost'],
                "x-rapidapi-key: ".$data['rapidapiKey']
            ],
        ]);

        return $curl;

    }
 
}