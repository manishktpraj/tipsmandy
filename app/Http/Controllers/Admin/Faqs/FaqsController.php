<?php

namespace App\Http\Controllers\Admin\Faqs;

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
use App\Models\Faq;

class FaqsController extends Controller
{
    use FormButtons, AuthorizationsTrait;

    protected $faqTbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');

        $this->faqTbl = new Faq;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Faq', 'Manage Faq');

        $renderButtons = $this->addButtons(route('admin.faqs.create'), 'manage-faqs');

        $faqs = $this->faqTbl::latest()->get(['id', 'title']);

        return view('admin.faqs.index', compact('renderButtons', 'faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Faq', 'Create Faq');

        $data['renderButtons'] = $this->addFormButtons(route('admin.faqs.index'));

        $data['backButtons'] = $this->backButtons(route('admin.faqs.index'));

        return view('admin.faqs.create', $data);
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_add'))
        {
            return abort(403);
        }

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $data['title'] = $slug = $request->title ? : NULL;
        $data['content'] = $request->content ? : NULL;
        $data['created_by'] = $this->adminId();

        $create = Faq::create($data);

        if($create) {

            return redirect()->route('admin.faqs.index')->with('success', 'Faq created successfully.');
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_edit'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Faq', 'Edit Faq');

        $data['faq'] = $this->findById($id);
        
        $data['renderButtons'] = $this->editFormButtons(route('admin.faqs.index'));

        $data['backButtons'] = $this->backButtons(route('admin.faqs.index'));

        return view('admin.faqs.edit', $data);
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_edit'))
        {
            return abort(403);
        }

        $faq = $this->findById($id);

        //Validation request data
        $request->validate([
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $data['title'] = $slug = $request->title ? : NULL;
        $data['content'] = $request->content ? : NULL;
        $data['created_by'] = $this->adminId();


        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'Faq updated successfully.');
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-faq', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
        
        $faq = $this->findById($id);
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'Faq deleted successfully.');
    }

    /**
     *
     * Faq detail find by id.
     */
    public function findById($id)
    {
        return  $this->faqTbl::findorFail($id);
    }
}
