<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Traits\Admin\FormButtons;
use App\Traits\Admin\AuthorizationsTrait;
use Exception;
use Helper;
use App\Models\Page;

class PagesController extends Controller
{

    use FormButtons, AuthorizationsTrait;

    protected $pageTbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->pageTbl = new Page;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Pages', 'Manage Pages');

        $renderButtons = $this->addButtons(route('admin.pages.create'), 'manage-pages');

        $pages = $this->pageTbl::latest()->get(['id', 'name']);

        return view('admin.pages.index', compact('renderButtons', 'pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);

        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Pages', 'Create Page');

        $data['renderButtons'] = $this->addFormButtons(route('admin.pages.index'));

        $data['backButtons'] = $this->backButtons(route('admin.pages.index'));

        return view('admin.pages.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);

        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_add'))
        {
            return abort(403);
        }
        
        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191',
            'content' => 'required',
        ]);

        $data['name'] = $slug = $request->name ? : NULL;
        $data['content'] = $request->content ? : NULL;
        $data['slug'] = Str::slug($slug, '-');
        $data['created_by'] = $this->adminId();

        $create = Page::create($data);

        if($create) {

            return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
        }

        return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');
    }

    private function adminId()
    {
        return Auth::guard('admin')->user()->id;
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_edit'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Pages', 'Edit Page');

        $data['page'] = $this->pageFindById($id);
        
        $data['renderButtons'] = $this->editFormButtons(route('admin.pages.index'));

        $data['backButtons'] = $this->backButtons(route('admin.pages.index'));

        return view('admin.pages.edit', $data);
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_edit'))
        {
            return abort(403);
        }
        
        $page = $this->pageFindById($id);

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:191',
            'content' => 'required',
        ]);

        $data['name'] = $slug = $request->name ? : NULL;
        $data['content'] = $request->content ? : NULL;
        $data['slug'] = Str::slug($slug, '-');


        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Page content updated successfully.');
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-pages', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
    }

    /**
     *
     * Page detail find by id.
     */
    public function pageFindById($id)
    {
        return  $this->pageTbl::findorFail($id);
    }
}
