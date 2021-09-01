<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Faq;
use Exception;

class FaqsController extends Controller
{
    protected $tbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tbl = new Faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $faqs = $this->tbl::latest()->get(['title', 'content']);
        
        $array = [];        

        $responseStatus = 202;
        
        $apiStatus = false;
        
        $message = 'Faq not found.';

        if(count($faqs)) {$responseStatus = 200; $message = 'Faq lists.'; $apiStatus = true;}

        foreach ($faqs as $key => $faq) {

            $array[$key]['title'] = $faq->title;
            $array[$key]['content'] = htmlspecialchars(strip_tags($faq->content));
            
        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'data' => $array
            ];

        return response()->json($response, $responseStatus);
    }
}
