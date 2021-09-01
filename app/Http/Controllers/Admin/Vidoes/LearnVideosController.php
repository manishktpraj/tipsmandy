<?php

namespace App\Http\Controllers\Admin\Vidoes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use App\Models\LearnVideo;
use App\Models\VideoCategory;
use App\Traits\UploadTrait;
use Exception;
use Helper;

class LearnVideosController extends Controller
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

        $this->tbl = new LearnVideo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Manage Videos');

        $renderButtons = $this->addButtons(route('admin.videos.create'), 'manage-videos');

        $videos = $this->tbl::latest()->get(['id', 'title', 'sub_title', 'video_url']);

        return view('admin.videos.index', compact('renderButtons', 'videos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Create Videos');

        $data['renderButtons'] = $this->addFormButtons(route('admin.videos.index'));

        $data['backButtons'] = $this->backButtons(route('admin.videos.index'));

        $data['videoCategories'] = $this->videoCategories();

        return view('admin.videos.create', $data);
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_add'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'video_url' => 'required',
            'content' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        $data['title'] = $request->title ? : NULL;
        $data['sub_title'] = $request->sub_title ? : NULL;
        $data['video_url'] = $request->video_url ? : NULL;
        $data['content'] = $request->content ? : NULL;
        
        if($request->has('icon')) {

            $icon = $request->file('icon');

            $image_path = public_path('uploads/icons');

            $data['icon'] =  $this->uploadOne($icon, public_path('uploads/icons'));

        }

        $create = $this->tbl::create($data);

        if($create) {

            return redirect()->route('admin.videos.index')->with('success', 'Learn videos created successfully.');
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_edit'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Videos', 'Edit Video');

        $data['video'] = $this->findById($id);
        
        $data['renderButtons'] = $this->editFormButtons(route('admin.videos.index'));

        $data['backButtons'] = $this->backButtons(route('admin.videos.index'));

        $data['videoCategories'] = $this->videoCategories();

        return view('admin.videos.edit', $data);
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_edit'))
        {
            return abort(403);
        }

        $video = $this->findById($id);

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'sub_title' => 'required|string',
            'video_url' => 'required',
            'content' => 'required',
            'icon' => 'nullable|image|mimes:jpeg,jpg,png',
        ]);

        $data['title'] = $request->title ? : NULL;
        $data['sub_title'] = $request->sub_title ? : NULL;
        $data['video_url'] = $request->video_url ? : NULL;
        $data['content'] = $request->content ? : NULL;
        
        if($request->has('icon')) {

            $icon = $request->file('icon');

            $image_path = public_path('uploads/icons');

            $oldIcon = $video->icon;

            //Unlik old file
            if(!empty($oldIcon) && File::exists($image_path.'/'.$oldIcon)) {

                unlink($image_path.'/'.$oldIcon);
            }
            
            $data['icon'] =  $this->uploadOne($icon, public_path('uploads/icons'));

        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Learn videos updated successfully.');
        
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-videos', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
        
        $this->findById($id)->delete();
        
        return redirect()->route('admin.videos.index')->with('success', 'Video deleted successfully.');
    }


    /**
     *
     * Faq detail find by id.
     */
    public function findById($id)
    {
        return  $this->tbl::findorFail($id);
    }


    private function videoCategories() {
        return VideoCategory::latest()->get(['id', 'title']);
    } 
}
