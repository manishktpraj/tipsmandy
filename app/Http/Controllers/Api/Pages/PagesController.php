<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Helper;
use Carbon\Carbon;
use App\Models\Page;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pages = DB::table('pages')->select('id', 'name', 'slug')->get();

        $dataArray = [];
        $responseStatus = 202;
        $apiStatus = false;

        $message = 'Data not found.';

        if(count($pages)) {$responseStatus = 200; $message = 'Data lists.'; $apiStatus = true;}

        foreach ($pages as $key => $row) {
            $dataArray[$key]['name'] = $row->name;
            $dataArray[$key]['url'] = url('/').'/'.$row->slug;
        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,
                'data' => $dataArray
            ];

        return response()->json($response, $responseStatus);
    }
}
