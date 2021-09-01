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
use Exception;
use Helper;

class CategoriesController extends Controller
{
    use FormButtons, AuthorizationsTrait;

    protected $tbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->tbl = new VideoCategory;
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

        $this->setPageTitle('Manage Videos Title', 'Manage Videos Title');

        $renderButtons = $this->addButtons(route('admin.videos.create'), 'manage-videos');

        $categories = $this->tbl::latest()->get(['id', 'title']);

        return view('admin.videos.categories.index', compact('renderButtons', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'title' => 'required|string|unique:video_categories'
        ]);

        $data['title'] = $slug = $request->title ? : NULL;
        $data['slug'] = Str::slug($slug, '-');
        
        $create = $this->tbl::create($data);

        if($create) {

            return redirect()->back()->with('success', 'Video title created successfully.');
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

        $videoTitle = $this->findById($id);

        $this->setPageTitle('Manage Videos Title', 'Manage Videos Title');

        $renderButtons = $this->addButtons(route('admin.videos.create'), 'manage-videos');

        $categories = $this->tbl::latest()->get(['id', 'title']);

        return view('admin.videos.categories.index', compact('renderButtons', 'categories', 'videoTitle'));
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
            'title' => 'required|string|unique:video_categories,title,'.$id
        ]);

        $data['title'] = $slug = $request->title ? : NULL;
        $data['slug'] = Str::slug($slug, '-');
        
        $video->update($data);

        return redirect()->route('admin.videos.categories.index')->with('success', 'Video title updated successfully.');
        
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
        
        return redirect()->route('admin.videos.categories.index')->with('success', 'Video title deleted successfully.');
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
