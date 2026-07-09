<?php
    
namespace App\Http\Controllers\admin;
    
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

    
class ContentController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:content-list|content-create|content-edit|content-delete', ['only' => ['index','show']]);
         $this->middleware('permission:content-create', ['only' => ['create','store']]);
         $this->middleware('permission:content-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:content-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $contents = Content::latest()->paginate(5);
        return view('contents.index',compact('contents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('contents.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        Content::create($request->all());
    
        return redirect()->route('contents.index')
                        ->with('success','Content created successfully.');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content): View
    {
        return view('contents.show',compact('content'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content): View
    {
        return view('contents.edit',compact('content'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content): RedirectResponse
    {
         request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $content->update($request->all());
    
        return redirect()->route('contents.index')
                        ->with('success','Content updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content): RedirectResponse
    {
        $content->delete();
    
        return redirect()->route('contents.index')
                        ->with('success','Content deleted successfully');
    }
}