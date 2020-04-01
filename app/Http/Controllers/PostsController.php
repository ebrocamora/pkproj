<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Posts;
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Posts::all();
       // $posts = Posts::all();
        //$posts = Posts::orderBy('title','asc')->get();
        $posts = DB::select('SELECT * from posts');
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        //handle the file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //define the filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload the image to database
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }
        $posts = new Posts;
        $posts->title = $request->input('title');
        $posts->body=$request->input('body');
        $posts->user_id = auth()->user()->id;
        $posts->cover_image = $fileNameToStore;
        $posts->save();

        return redirect('/posts')->with('success','Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $posts = Posts::find($id);
        return view('posts.show')->with('posts',$posts);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $posts = Posts::find($id);

         if(auth()->user()->id !== $posts->user_id){
            return redirect('/posts')->with('error','Unauthorizedd page.');
         };
        return view('posts.edit')->with('posts',$posts);

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
        $this->validate($request,[
            'title'=>'required',
            'body'=>'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        //handle the file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //define the filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload the image to database
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }
        $posts = Posts::find($id);
        $posts->title = $request->input('title');
        $posts->body=$request->input('body');
        if($request->hasFile('cover_image')){
            $posts->cover_image = $fileNameToStore;
        }
        $posts->save();

        return redirect('/posts')->with('success','Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posts = Posts::find($id);
        if(auth()->user()->id !== $posts->user_id){
            return redirect('/posts')->with('error','Unauthorizedd page.');
         }

         if($posts->cover_image != 'noimage.jpg'){
            Storage::delete('public/cover_image'.$posts->cover_image);
         }

        $posts->delete();
        return redirect('/posts')->with('success', 'Post Deleted');
    }
}
