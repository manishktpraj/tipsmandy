<?php

namespace App\Http\Controllers\Admin\Stockedgevideos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\StockEdgeVideo;
use App\Traits\UploadTrait;
use Exception;
use Helper;

class StockedgevideosController extends Controller
{
    use FormButtons, AuthorizationsTrait, UploadTrait;

    protected $tbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->tbl = new StockEdgeVideo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Manage Videos');

        $renderButtons = $this->addButtons(route('admin.stockedgevideos.create'), 'manage-videos');

        $videos = $this->tbl::latest()->get(['id', 'title', 'youtube_url', 'youtube_thumbnail']);

        return view('admin.stockedgevideos.index', compact('renderButtons', 'videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Create Videos');

        $data['renderButtons'] = $this->addFormButtons(route('admin.stockedgevideos.index'));

        $data['backButtons'] = $this->backButtons(route('admin.stockedgevideos.index'));

        return view('admin.stockedgevideos.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_add'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'video_url' => 'required',
            'youtube_thumbnail' => 'required',
        ]);

        $data['title'] = $request->title ? : NULL;
        $data['youtube_url'] = $request->video_url ? : NULL;
        
        if($request->has('youtube_thumbnail')) {

            $youtube_thumbnail = $request->file('youtube_thumbnail');

            $image_path = public_path('uploads/stockedgevideos');

            $data['youtube_thumbnail'] =  $this->uploadOne($youtube_thumbnail, public_path('uploads/stockedgevideos'));

        }

        $create = $this->tbl::create($data);

        if($create) {

            return redirect()->route('admin.stockedgevideos.index')->with('success', 'Video created successfully.');
        }

        return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_edit'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Edit Video');

        $data['video'] = $this->findById($id);
        
        $data['renderButtons'] = $this->editFormButtons(route('admin.stockedgevideos.index'));

        $data['backButtons'] = $this->backButtons(route('admin.stockedgevideos.index'));

        return view('admin.stockedgevideos.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_edit'))
        {
            return abort(403);
        }

        $video = $this->findById($id);

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'video_url' => 'required',
        ]);

        $data['title'] = $request->title ? : NULL;
        $data['youtube_url'] = $request->video_url ? : NULL;
        
        if($request->has('youtube_thumbnail')) {

            $youtube_thumbnail = $request->file('youtube_thumbnail');

            $image_path = public_path('uploads/stockedgevideos');

            $data['youtube_thumbnail'] =  $this->uploadOne($youtube_thumbnail, public_path('uploads/stockedgevideos'));

        }

        $video->update($data);

        return redirect()->route('admin.stockedgevideos.index')->with('success', 'Video updated successfully.');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-stockedgevideos', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
        
        $this->findById($id)->delete();
        
        return redirect()->route('admin.stockedgevideos.index')->with('success', 'Video deleted successfully.');
    }


    /**
     *
     * Faq detail find by id.
     */
    public function findById($id)
    {
        return  $this->tbl::findorFail($id);
    }
}
