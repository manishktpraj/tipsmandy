<?php

namespace App\Http\Controllers\Pages;

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
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $pageDetail = DB::table('pages')->select('name', 'content')->where('slug', $slug)->first();

        if(!$pageDetail) {
            return abort(404);
        }
        return view('pages.page-detail', compact('pageDetail'));
    }

}
