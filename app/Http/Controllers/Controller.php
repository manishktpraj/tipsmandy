<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $successStatus = 200, $notauthorized = 401;

    /**
     * @param $title
     * @param $subTitle
     */
    protected function setPageTitle($title, $subTitle)
    {
        view()->share(['pageTitle' => $title, 'subTitle' => $subTitle]);
    }


    /**
     * Get today date
     *
     * @return \Illuminate\Http\Response
     */
    protected function todayDate()
    {
        return Carbon::parse(now())->format('Y-m-d');
    }

    /**
     *
     * check if folder exists before create directory
     */
    public function createDirecrotory($path)
    {
        if(!\File::isDirectory($path)){

            \File::makeDirectory($path, 0777, true, true);

        }
    }


    protected function outputJSON($result = null, $responseCode = 200) {

        return response()->json($result, $responseCode);
    }


    /**
     * Check ajax request valid or not
     *
     */
    protected function invalidajaxRequest()
    {
        //Check if request type is invalid

        $response = [
            'status' => 'fail',
            'reason' => [
                'reason' => 'invalid request type',
            ],

            'response_time' => Carbon::now()->toDateTimeString()

        ];

        header('Content-type: application/json');

        http_response_code(401);

        return response()->json($response);
    }


    protected function adminProfile()
    {
        return \Auth::guard('admin')->user();
    }
}
